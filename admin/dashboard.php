<?php
session_start();
require_once '../db.php';

// Check if admin is logged in
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header('Location: ../index.php');
    exit;
}

// Fetch stats
$donorsCount = $pdo->query("SELECT COUNT(*) FROM users WHERE role='donor'")->fetchColumn() ?: 0;
$campaignsCount = $pdo->query("SELECT COUNT(*) FROM campaigns")->fetchColumn() ?: 0;
$alertsCount = $pdo->query("SELECT COUNT(*) FROM alerts")->fetchColumn() ?: 0;

// Fetch recent alerts
$alerts = $pdo->query("SELECT * FROM alerts ORDER BY created_at DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC) ?: [];
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard - Blood Donation</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { background-color: #f8f9fa; }
.navbar { background-color: #dc3545; }
.navbar-brand, .navbar-nav .nav-link { color: white !important; }
.card-stats { border-top: 5px solid #dc3545; border-radius: 0.75rem; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
.card-stats h5 { color: #dc3545; }
</style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Blood Donation Admin</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link active" href="dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="donors.php">Donors</a></li>
        <li class="nav-item"><a class="nav-link" href="campaigns.php">Campaigns</a></li>
        <li class="nav-item"><a class="nav-link" href="alerts.php">Alerts</a></li>
        <li class="nav-item"><a class="nav-link" href="analytics.php">Analytics</a></li>
        <li class="nav-item"><a class="nav-link" href="inventory.php">Inventory</a></li>
        <li class="nav-item"><a class="nav-link" href="requests.php">Requests</a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Main container -->
<div class="container py-5">
    <h3 class="mb-4 text-center text-danger">Admin Dashboard</h3>

    <div class="row g-4">
        <!-- Donors Card -->
        <div class="col-md-4">
            <div class="card card-stats p-3 text-center">
                <i class="bi bi-people-fill fs-1"></i>
                <h5 class="mt-2">Total Donors</h5>
                <p class="fs-4"><?= htmlspecialchars($donorsCount) ?></p>
            </div>
        </div>
        <!-- Campaigns Card -->
        <div class="col-md-4">
            <div class="card card-stats p-3 text-center">
                <i class="bi bi-calendar2-event-fill fs-1"></i>
                <h5 class="mt-2">Active Campaigns</h5>
                <p class="fs-4"><?= htmlspecialchars($campaignsCount) ?></p>
            </div>
        </div>
        <!-- Alerts Card -->
        <div class="col-md-4">
            <div class="card card-stats p-3 text-center">
                <i class="bi bi-exclamation-triangle-fill fs-1"></i>
                <h5 class="mt-2">Alerts</h5>
                <p class="fs-4"><?= htmlspecialchars($alertsCount) ?></p>
            </div>
        </div>
    </div>

    <!-- Recent Alerts -->
    <div class="mt-5">
        <h5 class="text-danger mb-3">Recent Alerts</h5>
        <div class="list-group">
            <?php if($alerts): ?>
                <?php foreach($alerts as $alert): ?>
                    <div class="list-group-item list-group-item-action">
                        <p class="mb-1 text-danger"><?= htmlspecialchars($alert['message'] ?? '') ?></p>
                        <small><?= date('d M Y', strtotime($alert['created_at'] ?? '')) ?></small>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="list-group-item">No alerts yet.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
