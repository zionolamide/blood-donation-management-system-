<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function require_login(){
    if(!isset($_SESSION['user_id'])){
        header("Location: ../index.php");
        exit;
    }
}

function require_admin(){
    require_login();
    if(($_SESSION['role'] ?? '') !== 'admin'){
        die("Access denied. Admins only.");
    }
}

function require_donor(){
    require_login();
    if(($_SESSION['role'] ?? '') !== 'donor'){
        die("Access denied. Donors only.");
    }
}
