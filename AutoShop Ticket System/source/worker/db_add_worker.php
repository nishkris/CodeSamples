<?php
include('../login/session_check.php');
include "../db_connector.php";
include "../constants.php";
$workerEmpId = trim($_POST["formWorkerEmpId"]);
$workerName = trim($_POST["formWorkerName"]);
$workerPhone = trim($_POST["formWorkerPhone"]);
$workerAddress = trim($_POST["formWorkerAddress"]);
$workerEmail = trim($_POST["formWorkerEmail"]);
$workerUser = trim($_POST["formWorkerUser"]);
$workerPw = trim($_POST["formWorkerPassword"]);
$offendingChars = "~`!#$%\^&*+=\[\]\';\/{}|\":<>\?";

$hashedPassword = crypt($workerPw);

$is_update = htmlspecialchars($_GET["update"]);
$empToUpdate = htmlspecialchars($_GET["eid"]);

if ($workerEmpId == "" || $workerName == "" || $workerPhone == "") {
    echo "<p>Employee ID, Name and Phone Number are mandatory</p>";
} else if (strpbrk($workerEmpId, $offendingChars) || strpbrk($workerName, $offendingChars) ||
    strpbrk($workerPhone, $offendingChars)
) {
    echo "<p>No special characters are allowed on Employee ID, Name and Phone Number</p>";
} else {
    if ($is_update == 'true') {
        $query1 = "update internal_user set eid='$workerEmpId', role='$role_worker' where eid='$empToUpdate';";
        $query2 = "update worker set eid='$workerEmpId', name='$workerName', phone='$workerPhone',
        address='$workerAddress', email='$workerEmail' where eid='$workerEmpId';";
    } else {
        $query1 = "insert into internal_user values('$workerEmpId', '$workerUser', '$hashedPassword', '$role_worker');";
        $query2 = "insert into worker values('$workerEmpId', '$workerName', '$workerPhone',
        '$workerAddress', '$workerEmail');";
    }
    if (mysql_query($query1) && mysql_query($query2)) {
        header("Location: " . $server_host . "autoshop/worker/worker.php");
        exit();
    } else {
        echo "<p>There is a problem with the query: " . mysql_error() . "</p>";
    }
}
?>