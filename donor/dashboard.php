<?php
session_start();
require_once '../db.php';
require_once '../auth.php';
require_login(); // ensures donor is logged in

if($_SESSION['role'] !== 'donor'){
    header('Location: ../index.php');
    exit;
}

$donor_id = $_SESSION['user_id'];

// Fetch donor info
$donor = $pdo->prepare("SELECT * FROM users WHERE id=?");
$donor->execute([$donor_id]);
$donor = $donor->fetch(PDO::FETCH_ASSOC);

// Fetch total donations
$totalDonations = $pdo->prepare("SELECT COUNT(*) FROM donations WHERE donor_id=?");
$totalDonations->execute([$donor_id]);
$totalDonations = $totalDonations->fetchColumn();

// Fetch recent alerts
$alerts = $pdo->query("SELECT * FROM alerts ORDER BY created_at DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

// Fetch upcoming campaigns
$campaigns = $pdo->query("SELECT * FROM campaigns WHERE campaign_date >= CURDATE() ORDER BY campaign_date ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Donor Dashboard - BDMS</title>
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
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">BDMS Donor</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link active" href="#">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="donations.php">My Donations</a></li>
        <li class="nav-item"><a class="nav-link" href="campaigns.php">Campaigns</a></li>
        <li class="nav-item"><a class="nav-link" href="alerts.php">Alerts</a></li>
        <li class="nav-item"><a class="nav-link" href="inventory.php">Inventory</a></li>
        <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
        <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container py-5">
    <h3 class="mb-4 text-center text-danger">Welcome, <?php echo htmlspecialchars($donor['name']); ?>!</h3>

    <!-- Stats -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card p-3 text-center">
                <h5>Total Donations</h5>
                <p class="fs-4"><?php echo $totalDonations; ?></p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 text-center">
                <h5>Blood Group</h5>
                <p class="fs-4"><?php echo htmlspecialchars($donor['blood_group']); ?></p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 text-center">
                <h5>Phone</h5>
                <p class="fs-4"><?php echo htmlspecialchars($donor['phone']); ?></p>
            </div>
        </div>
    </div>

    <!-- Upcoming Campaigns -->
    <div class="card mb-4 p-3">
        <h5 class="text-danger mb-3">Upcoming Campaigns</h5>
        <div class="list-group">
            <?php if($campaigns): ?>
                <?php foreach($campaigns as $c): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <?php echo htmlspecialchars($c['title']); ?> - <?php echo htmlspecialchars($c['location']); ?>
                        <span class="badge bg-danger"><?php echo date('d M Y', strtotime($c['campaign_date'])); ?></span>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="list-group-item text-center">No upcoming campaigns.</div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Recent Alerts -->
    <div class="card p-3">
        <h5 class="text-danger mb-3">Recent Alerts</h5>
        <div class="list-group">
            <?php if($alerts): ?>
                <?php foreach($alerts as $a): ?>
                    <div class="list-group-item">
                        <strong><?php echo htmlspecialchars($a['title']); ?></strong>
                        <p class="mb-0"><?php echo htmlspecialchars($a['body']); ?></p>
                        <small class="text-muted"><?php echo date('d M Y', strtotime($a['created_at'])); ?></small>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="list-group-item text-center">No alerts available.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
