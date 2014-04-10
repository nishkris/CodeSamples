<?php
include('../login/session_check.php');
include "../db_connector.php";
include "../constants.php";

$brand = $_POST['brand'];
$model = $_POST['model'];

$sql = "SELECT distinct trim from vehicle_model where brand='".$brand."' and model='".$model."'";
$result = mysql_query($sql);
$num = mysql_numrows($result);
$arr = array();

for ($i = 0; $i < $num; $i++) {
    $arr[$i] = mysql_result($result, $i, 'trim');
}

echo json_encode($arr);
?>