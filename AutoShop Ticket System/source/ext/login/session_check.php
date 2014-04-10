<?php
include "../../constants.php";

session_start();
if (!(isset($_SESSION['cid'])))
{
    header("Location: " . $ext_context . "login/login.php");
    die();
}

?> 

