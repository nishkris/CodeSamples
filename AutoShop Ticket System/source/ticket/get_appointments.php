<?php
include('../login/session_check.php');
include "../db_connector.php";
include "../constants.php";

$vin = $_POST['vin'];

$sql = "SELECT date, time from appointment where vehicle='".$vin."'";
$result = mysql_query($sql);
$num = mysql_numrows($result);
$arr = array();

for ($i = 0; $i < $num; $i++) {
    $date = mysql_result($result, $i, 'date');
    $time = mysql_result($result, $i, 'time');
    $arr[$i] = $date." / ".$time;;
}

echo json_encode($arr);
?>