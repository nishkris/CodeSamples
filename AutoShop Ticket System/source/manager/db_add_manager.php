<?php
include('../login/session_check.php');
include "../db_connector.php";
include "../constants.php";
$managerEmpId = trim($_POST["formManagerEmpId"]);
$managerName = trim($_POST["formManagerName"]);
$managerPhone = trim($_POST["formManagerPhone"]);
$managerAddress = trim($_POST["formManagerAddress"]);
$managerEmail = trim($_POST["formManagerEmail"]);
$managerUser = trim($_POST["formManagerUser"]);
$managerPw = trim($_POST["formManagerPassword"]);
$offendingChars = "~`!#$%\^&*+=\[\]\';\/{}|\":<>\?";

$hashedPassword = crypt($managerPw);

$is_update = htmlspecialchars($_GET["update"]);
$empToUpdate = htmlspecialchars($_GET["eid"]);

if ($managerEmpId == "" || $managerName == "" || $managerPhone == "") {
    echo "<p>Employee ID, Name and Phone Number are mandatory</p>";
} else if (strpbrk($managerEmpId, $offendingChars) || strpbrk($managerName, $offendingChars) ||
    strpbrk($managerPhone, $offendingChars)
) {
    echo "<p>No special characters are allowed on Employee ID, Name and Phone Number</p>";
} else {
    if ($is_update == 'true') {
        $query1 = "update internal_user set eid='$managerEmpId', role='$role_manager' where eid='$empToUpdate';";
        $query2 = "update manager set eid='$managerEmpId', name='$managerName', phone='$managerPhone',
        address='$managerAddress', email='$managerEmail' where eid='$managerEmpId';";
    } else {
        $query1 = "insert into internal_user values('$managerEmpId', '$managerUser', '$hashedPassword', '$role_manager');";
        $query2 = "insert into manager values('$managerEmpId', '$managerName', '$managerPhone',
        '$managerAddress', '$managerEmail');";
    }
    if (mysql_query($query1) && mysql_query($query2)) {
        header("Location: " . $server_host . "autoshop/manager/manager.php");
        exit();
    } else {
        echo "<p>There is a problem with the query: " . mysql_error() . "<p>";
    }
}
?>