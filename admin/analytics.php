<?php
session_start();
require_once '../db.php';

// Only admin access
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header('Location: ../index.php');
    exit;
}

// Fetch statistics
$totalDonors = $pdo->query("SELECT COUNT(*) FROM users WHERE role='donor'")->fetchColumn();
$totalCampaigns = $pdo->query("SELECT COUNT(*) FROM campaigns")->fetchColumn();
$totalAlerts = $pdo->query("SELECT COUNT(*) FROM alerts")->fetchColumn();

// Donor blood group distribution
$bloodGroups = $pdo->query("SELECT blood_group, COUNT(*) AS count FROM users WHERE role='donor' GROUP BY blood_group")->fetchAll(PDO::FETCH_ASSOC);

// Recent donations
$recentDonations = $pdo->query("SELECT d.*, u.name FROM donations d JOIN users u ON d.donor_id=u.id ORDER BY d.donation_date DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Analytics - Blood Donation Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
body { background-color:#f8f9fa; }
.navbar { background-color: #dc3545; }
.navbar-brand, .navbar-nav .nav-link { color: white !important; }
.card-stats { border-top: 5px solid #dc3545; border-radius:0.75rem; box-shadow:0 5px 15px rgba(0,0,0,0.1); }
</style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand" href="dashboard.php">Blood Donation Admin</a>
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

<div class="container py-5">
    <h3 class="mb-4 text-center text-danger">Analytics</h3>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card card-stats p-3 text-center">
                <h5>Total Donors</h5>
                <p class="fs-4"><?php echo $totalDonors; ?></p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-stats p-3 text-center">
                <h5>Total Campaigns</h5>
                <p class="fs-4"><?php echo $totalCampaigns; ?></p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-stats p-3 text-center">
                <h5>Total Alerts</h5>
                <p class="fs-4"><?php echo $totalAlerts; ?></p>
            </div>
        </div>
    </div>

    <!-- Blood Group Distribution -->
    <div class="mt-5">
        <h5 class="text-danger mb-3">Donor Blood Group Distribution</h5>
        <canvas id="bloodChart" height="120"></canvas>
    </div>

    <!-- Recent Donations -->
    <?php if($recentDonations): ?>
    <div class="mt-5">
        <h5 class="text-danger mb-3">Recent Donations</h5>
        <div class="list-group">
            <?php foreach($recentDonations as $d): ?>
                <div class="list-group-item">
                    <strong><?php echo htmlspecialchars($d['name']); ?></strong> donated <strong><?php echo htmlspecialchars($d['quantity']); ?>ml</strong> on <?php echo $d['donation_date']; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const ctx = document.getElementById('bloodChart').getContext('2d');
const bloodData = {
    labels: <?php echo json_encode(array_column($bloodGroups,'blood_group')); ?>,
    datasets: [{
        label: 'Number of Donors',
        data: <?php echo json_encode(array_column($bloodGroups,'count')); ?>,
        backgroundColor: [
            '#dc3545','#e83e8c','#fd7e14','#ffc107','#198754','#0dcaf0','#6610f2','#6c757d'
        ]
    }]
};
new Chart(ctx, {
    type: 'pie',
    data: bloodData,
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } }
    }
});
</script>

</body>
</html>
