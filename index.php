<?php
session_start();
require_once 'db.php'; // Make sure this file creates $pdo PDO connection

$err = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';

    // Fetch user from database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Plaintext password check (for dummy data)
    if ($user && $user['password'] === $pass) {
        // Set session variables
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on role
        if ($user['role'] === 'admin') {
            header('Location: admin/dashboard.php');
        } elseif ($user['role'] === 'donor') {
            header('Location: donor/dashboard.php');
        } else { // recipient
            header('Location: recipient/dashboard.php');
        }
        exit;
    } else {
        $err = "Invalid email or password.";
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Blood Donation Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
body {
    background: linear-gradient(to right, #ff4b5c, #ff6b81);
    min-height: 100vh;
    display: flex;
    align-items: center;
    font-family: 'Segoe UI', sans-serif;
}
.card-login {
    border-radius: 1rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    border-top: 5px solid #dc3545;
}
.card-login h3 {
    color: #dc3545;
    font-weight: 700;
}
.btn-primary {
    background-color: #dc3545;
    border-color: #dc3545;
}
.btn-primary:hover {
    background-color: #b71c1c;
    border-color: #b71c1c;
}</style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card card-login p-4">
                <h3 class="text-center mb-3"><i class="bi bi-droplet-half"></i> Blood Donation System</h3>

                <?php if($err): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($err); ?></div>
                <?php endif; ?>

                <form method="post">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-box-arrow-in-right"></i> Login</button>
                </form>

                <p class="text-center mt-3 text-white small">
                    © 2025 Blood Donation Management System
                </p>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
