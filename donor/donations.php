<?php
require_once '../db.php';
require_once '../auth.php';
require_donor(); // Make sure only donors can access

// Handle new donation
$err = $ok = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $blood_group = trim($_POST['blood_group'] ?? '');
    $quantity    = trim($_POST['quantity'] ?? '');
    $donation_date = trim($_POST['donation_date'] ?? '');

    if ($blood_group && $quantity && $donation_date) {
        $stmt = $pdo->prepare("INSERT INTO donations (donor_id, blood_group, quantity, donation_date) VALUES (?,?,?,?)");
        $stmt->execute([$_SESSION['user_id'], $blood_group, $quantity, $donation_date]);
        $ok = "Donation recorded successfully.";
    } else {
        $err = "All fields are required.";
    }
}

// Fetch donor's past donations
$donations = $pdo->prepare("SELECT * FROM donations WHERE donor_id=? ORDER BY donation_date DESC");
$donations->execute([$_SESSION['user_id']]);
$donations = $donations->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Donations - BDMS Donor</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background-color:#f8f9fa; }
.navbar { background-color: #dc3545; }
.navbar-brand, .navbar-nav .nav-link { color: white !important; }
.card { border-radius:0.75rem; box-shadow:0 5px 15px rgba(0,0,0,0.1); }
</style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand" href="dashboard.php">BDMS Donor</a>
    <div class="ms-auto">
      <a class="btn btn-outline-light btn-sm me-2" href="dashboard.php">Dashboard</a>
      <a class="btn btn-outline-light btn-sm" href="../logout.php">Logout</a>
    </div>
  </div>
</nav>

<div class="container py-5">
    <h3 class="mb-4 text-center text-danger">Record a Donation</h3>

    <?php if($err) echo "<div class='alert alert-danger'>$err</div>"; ?>
    <?php if($ok) echo "<div class='alert alert-success'>$ok</div>"; ?>

    <!-- Donation Form -->
    <div class="card p-4 mb-4">
        <form method="post" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="blood_group" class="form-control" placeholder="Blood Group (e.g., O+)" required>
            </div>
            <div class="col-md-4">
                <input type="number" name="quantity" class="form-control" placeholder="Quantity (ml)" required>
            </div>
            <div class="col-md-4">
                <input type="date" name="donation_date" class="form-control" required>
            </div>
            <div class="col-12">
                <button class="btn btn-success w-100">Submit Donation</button>
            </div>
        </form>
    </div>

    <!-- Past Donations -->
    <h5 class="mb-3">Your Past Donations</h5>
    <div class="card p-3">
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Blood Group</th>
                    <th>Quantity (ml)</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if($donations): foreach($donations as $i => $d): ?>
                    <tr>
                        <td><?php echo $i+1; ?></td>
                        <td><?php echo htmlspecialchars($d['blood_group']); ?></td>
                        <td><?php echo htmlspecialchars($d['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($d['donation_date']); ?></td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="4" class="text-center">No donations recorded yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
