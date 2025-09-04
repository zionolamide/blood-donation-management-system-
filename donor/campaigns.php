<?php
require_once '../db.php';
require_once '../auth.php';
require_donor(); // Only donors can access

// Fetch upcoming campaigns
$campaigns = $pdo->query("SELECT * FROM campaigns ORDER BY campaign_date ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Campaigns - BDMS Donor</title>
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
    <h3 class="mb-4 text-center text-danger">Upcoming Blood Donation Campaigns</h3>

    <div class="row g-4">
        <?php if($campaigns): foreach($campaigns as $c): ?>
        <div class="col-md-4">
            <div class="card p-3">
                <h5 class="card-title"><?php echo htmlspecialchars($c['title']); ?></h5>
                <p class="mb-1"><strong>Location:</strong> <?php echo htmlspecialchars($c['location']); ?></p>
                <p class="mb-1"><strong>Date:</strong> <?php echo htmlspecialchars($c['campaign_date']); ?></p>
            </div>
        </div>
        <?php endforeach; else: ?>
        <div class="col-12">
            <div class="alert alert-info text-center">No upcoming campaigns at the moment.</div>
        </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
