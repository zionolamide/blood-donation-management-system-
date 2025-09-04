<?php
session_start();
require_once '../db.php';
require_once '../auth.php';
require_login(); // ensure donor is logged in

$err = $ok = null;

// Fetch donor info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id=? AND role='donor'");
$stmt->execute([$_SESSION['user_id']]);
$donor = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$donor) {
    header('Location: ../index.php');
    exit;
}

// Handle profile update
if($_SERVER['REQUEST_METHOD']==='POST'){
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $pass  = trim($_POST['password'] ?? '');
    
    if($name && $email){
        if($pass){
            $stmt = $pdo->prepare("UPDATE users SET name=?, email=?, phone=?, password=? WHERE id=?");
            $stmt->execute([$name,$email,$phone,$pass,$donor['id']]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET name=?, email=?, phone=? WHERE id=?");
            $stmt->execute([$name,$email,$phone,$donor['id']]);
        }
        $ok = "Profile updated successfully.";
    } else {
        $err = "Name and Email are required.";
    }
    // Refresh donor info
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id=? AND role='donor'");
    $stmt->execute([$_SESSION['user_id']]);
    $donor = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Profile - BDMS Donor</title>
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
        <a class="btn btn-outline-light btn-sm" href="logout.php">Logout</a>
    </div>
</div>
</nav>

<div class="container py-5">
<div class="row justify-content-center">
<div class="col-md-6">
<div class="card p-4">
<h4 class="mb-3 text-center text-danger">My Profile</h4>

<?php if($err) echo "<div class='alert alert-danger'>$err</div>"; ?>
<?php if($ok) echo "<div class='alert alert-success'>$ok</div>"; ?>

<form method="post">
<div class="mb-2"><input type="text" name="name" class="form-control" placeholder="Full Name" value="<?php echo htmlspecialchars($donor['name']); ?>" required></div>
<div class="mb-2"><input type="email" name="email" class="form-control" placeholder="Email" value="<?php echo htmlspecialchars($donor['email']); ?>" required></div>
<div class="mb-2"><input type="text" name="phone" class="form-control" placeholder="Phone" value="<?php echo htmlspecialchars($donor['phone']); ?>"></div>
<div class="mb-3"><input type="password" name="password" class="form-control" placeholder="Leave blank to keep password"></div>
<button class="btn btn-danger w-100">Update Profile</button>
</form>

</div>
</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
