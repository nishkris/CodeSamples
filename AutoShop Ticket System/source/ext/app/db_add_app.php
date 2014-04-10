<?php
include('../login/session_check.php');
include "../../db_connector.php";
include "../../constants.php";
$appVin = trim($_POST["app_vin"]);
$appDate = trim($_POST["app_date"]);
$appTime = trim($_POST["app_time"]);
$appWorker = trim($_POST["app_worker"]);
$offendingChars = "~`!#$%\^&*+=\[\]\';\/{}|\":<>\?";

$is_update = htmlspecialchars($_GET["update"]);
$appToUpdate = htmlspecialchars($_GET["aid"]);

if ($appVin == "" || $appDate == "" || $appTime == "" || $appWorker == "") {
    echo "<p>VIN, Date, Time and Worker are mandatory</p>";
} else {
    // first get the eid of the worker using his name
    $w_query = "select eid from worker where name='$appWorker';";
    $w_result = mysql_query($w_query);
    if (!$w_result) {
        echo "<p>There is a problem with the query: " . mysql_error() . "<p>";
        exit();
    }
    $w_eid = mysql_result($w_result, 0, 'eid');

    if ($is_update == 'true') {
        $query = "update appointment set date='$appDate', time='$appTime', vehicle='$appVin', worker='$w_eid' where aid='$appToUpdate';";
    } else {
        $query = "insert into appointment values(0, '$appDate', '$appTime', '$appVin', '$w_eid');";
    }
    if (mysql_query($query)) {
        header("Location: " . $ext_context. "app/appointment.php");
        exit();
    } else {
        echo "<p>There is a problem with the query: " . mysql_error() . "<p>";
    }
}
?>