<?php
include "../../constants.php";
// destroy the current session and redirect to login page
session_start();
session_destroy();

header("Location: " . $ext_context . "login/login.php");
?>
