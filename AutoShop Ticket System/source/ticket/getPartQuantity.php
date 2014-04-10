<?php
include('../login/session_check.php');
include "../db_connector.php";
include "../constants.php";

$pid = $_POST['pid'];

$sql = "SELECT in_stock FROM part WHERE pid='".$pid."'";
$result = mysql_query($sql);
$num = mysql_numrows($result);
$arr = array();
$quant = mysql_result($result, 0, 'in_stock');
$arr[0] = $quant;
/* for ($i = 0; $i < $num; $i++) {
 $date = mysql_result($result, $i, 'date');
$time = mysql_result($result, $i, 'time');
$arr[$i] = $date." / ".$time;;
} */

echo json_encode($arr);
?>