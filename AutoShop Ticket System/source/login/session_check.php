<?php
include "../constants.php";

session_start();

if (!(isset($_SESSION['eid']))) {
    header("Location: " . $server_host . "autoshop/login/login.php");
    die();
}

function is_manager() {
    global $role_manager;
    if ($_SESSION['role'] == $role_manager) {
        return true;
    }
    return false;
}

function is_worker() {
    global $role_worker;
    if ($_SESSION['role'] == $role_worker) {
        return true;
    }
    return false;
}
?> 

