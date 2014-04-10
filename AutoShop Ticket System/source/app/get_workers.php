<?php
include('../login/session_check.php');
include "../db_connector.php";
include "../constants.php";

$vin = $_POST['vin'];
$date = $_POST['date'];
$time = $_POST['time'].":00";

// we have to select a worker who is an expert for the model of the given vehicle
// and also, the worker should not have any appointments at the selected time.
$sql = "SELECT w.name from vehicle v, worker w, expertise e where v.vin='".$vin."' and
v.model=e.model and e.worker=w.eid and w.eid not in (select worker from appointment where date='".$date."' and time='".$time."');";
$result = mysql_query($sql);
if (!$result) {
    echo mysql_error();
    echo $sql;
}
$num = mysql_numrows($result);
$arr = array();

for ($i = 0; $i < $num; $i++) {
    $arr[$i] = mysql_result($result, $i, 'name');
}

echo json_encode($arr);
?>