<?php
require_once '../db.php';
require_once '../auth.php';
require_donor(); // Only donors can access

// Fetch all alerts, most recent first
$alerts = $pdo->query("SELECT * FROM alerts ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Alerts - BDMS Donor</title>
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
    <h3 class="mb-4 text-center text-danger">Blood Donation Alerts</h3>

    <div class="row g-4">
        <?php if($alerts): foreach($alerts as $a): ?>
        <div class="col-md-6">
            <div class="card p-3">
                <h5 class="card-title text-danger"><?php echo htmlspecialchars($a['title']); ?></h5>
                <p class="card-text"><?php echo htmlspecialchars($a['body']); ?></p>
                <small class="text-muted">Posted on <?php echo htmlspecialchars(date('F j, Y', strtotime($a['created_at']))); ?></small>
            </div>
        </div>
        <?php endforeach; else: ?>
        <div class="col-12">
            <div class="alert alert-info text-center">No alerts at the moment.</div>
        </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
