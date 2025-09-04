<?php
session_start();
require_once '../db.php';

// Only admin access
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header('Location: ../index.php');
    exit;
}

// Fetch all requests
$requests = $pdo->query("
    SELECT r.id, u.name AS donor_name, r.blood_group, r.quantity, r.hospital, r.status, r.request_date
    FROM requests r
    JOIN users u ON r.donor_id = u.id
    ORDER BY r.request_date DESC
")->fetchAll(PDO::FETCH_ASSOC);

// Handle approve/reject actions
if(isset($_GET['action'], $_GET['id'])){
    $id = intval($_GET['id']);
    if($_GET['action'] === 'approve'){
        $pdo->prepare("UPDATE requests SET status='Approved' WHERE id=?")->execute([$id]);
    } elseif($_GET['action'] === 'reject'){
        $pdo->prepare("UPDATE requests SET status='Rejected' WHERE id=?")->execute([$id]);
    }
    header('Location: requests.php');
    exit;
}
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Blood Requests - BDMS Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background-color:#f8f9fa; }
.navbar { background-color: #dc3545; }
.navbar-brand, .navbar-nav .nav-link { color: white !important; }
.card { border-radius:0.75rem; box-shadow:0 5px 15px rgba(0,0,0,0.1); }
.status-Pending { color: #ffc107; font-weight:bold; }
.status-Approved { color: #198754; font-weight:bold; }
.status-Rejected { color: #dc3545; font-weight:bold; }
.table-responsive { overflow-x:auto; }
</style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="dashboard.php">BDMS Admin</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="donors.php">Donors</a></li>
        <li class="nav-item"><a class="nav-link" href="campaigns.php">Campaigns</a></li>
        <li class="nav-item"><a class="nav-link" href="alerts.php">Alerts</a></li>
        <li class="nav-item"><a class="nav-link" href="analytics.php">Analytics</a></li>
        <li class="nav-item"><a class="nav-link" href="inventory.php">Inventory</a></li>
        <li class="nav-item"><a class="nav-link active" href="requests.php">Requests</a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Main container -->
<div class="container py-5">
    <h3 class="mb-4 text-center text-danger">Blood Requests</h3>
    <div class="card p-3">
        <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Donor</th>
                    <th>Blood Group</th>
                    <th>Quantity (ml)</th>
                    <th>Hospital</th>
                    <th>Status</th>
                    <th>Request Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($requests as $i => $r): ?>
                <tr>
                    <td><?php echo $i+1; ?></td>
                    <td><?php echo htmlspecialchars($r['donor_name']); ?></td>
                    <td><?php echo htmlspecialchars($r['blood_group']); ?></td>
                    <td><?php echo htmlspecialchars($r['quantity']); ?></td>
                    <td><?php echo htmlspecialchars($r['hospital']); ?></td>
                    <td class="status-<?php echo $r['status']; ?>"><?php echo $r['status']; ?></td>
                    <td><?php echo $r['request_date']; ?></td>
                    <td>
                        <?php if($r['status']=='Pending'): ?>
                            <div class="d-flex flex-wrap gap-1">
                                <a href="?action=approve&id=<?php echo $r['id']; ?>" class="btn btn-sm btn-success">Approve</a>
                                <a href="?action=reject&id=<?php echo $r['id']; ?>" class="btn btn-sm btn-danger">Reject</a>
                            </div>
                        <?php else: ?>
                            <span class="text-muted">No actions</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($requests)): ?>
                <tr><td colspan="8" class="text-center">No requests found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
