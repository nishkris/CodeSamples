<?php
include "../constants.php";
// destroy the current session and redirect to login page
session_start();

if ($_SESSION['role'] == $role_customer) {
    $login_url = $server_host . "autoshop/login_customer/login.php";
} else {
    $login_url = $server_host . "autoshop/login/login.php";
}

session_destroy();

header("Location: " . $login_url);
?>