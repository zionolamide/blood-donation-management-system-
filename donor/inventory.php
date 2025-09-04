<?php
session_start();
require_once '../db.php';
require_once '../auth.php';
require_login(); // ensure donor is logged in

// Fetch blood inventory
$inventory = $pdo->query("SELECT * FROM blood_inventory ORDER BY blood_group ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Blood Inventory - BDMS Donor</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background-color:#f8f9fa; }
.navbar { background-color: #dc3545; }
.navbar-brand, .navbar-nav .nav-link { color: white !important; }
.card { border-radius:0.75rem; box-shadow:0 5px 15px rgba(0,0,0,0.1); }
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
<div class="container-fluid">
    <a class="navbar-brand" href="dashboard.php">BDMS Donor</a>
    <div class="ms-auto">
        <a class="btn btn-outline-light btn-sm me-2" href="donations.php">Donations</a>
        <a class="btn btn-outline-light btn-sm me-2" href="campaigns.php">Campaigns</a>
        <a class="btn btn-outline-light btn-sm me-2" href="alerts.php">Alerts</a>
        <a class="btn btn-outline-light btn-sm me-2" href="profile.php">Profile</a>
        <a class="btn btn-outline-light btn-sm" href="logout.php">Logout</a>
    </div>
</div>
</nav>

<div class="container py-5">
<h3 class="mb-4 text-center text-danger">Blood Inventory</h3>

<div class="card p-4">
<table class="table table-striped align-middle">
<thead class="table-dark">
<tr>
<th>#</th>
<th>Blood Group</th>
<th>Quantity (ml)</th>
<th>Last Updated</th>
</tr>
</thead>
<tbody>
<?php foreach($inventory as $i => $item): ?>
<tr>
<td><?php echo $i+1; ?></td>
<td><?php echo htmlspecialchars($item['blood_group']); ?></td>
<td><?php echo htmlspecialchars($item['quantity']); ?></td>
<td><?php echo htmlspecialchars($item['last_updated']); ?></td>
</tr>
<?php endforeach; ?>
<?php if(empty($inventory)): ?>
<tr><td colspan="4" class="text-center">No inventory records found.</td></tr>
<?php endif; ?>
</tbody>
</table>
</div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
