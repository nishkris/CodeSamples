<?php
include('../login/session_check.php');
include "../db_connector.php";
include "../constants.php";

$s_sum = $_POST['sum'];
$s_vin = $_POST['vin'];
$s_worker = $_POST['worker'];
$s_status = $_POST['status'];
$s_from = $_POST['from'];
$s_to = $_POST['to'];

$where = "";
$flag = false;
if ($s_sum != null && $s_sum != '') {
    $where .= "t.summary like '%" . $s_sum . "%'";
    $flag = true;
}
if ($s_vin != null && $s_vin != 'Any' && substr($s_vin, 0, 2) != '--') {
    if (!$flag) {
        $flag = true;
    } else {
        $where .= " and";
    }
    $where .= " t.vehicle = '" . $s_vin . "'";
}
if ($s_status != null && $s_status != 'Any' && substr($s_status, 0, 2) != '--') {
    if (!$flag) {
        $flag = true;
    } else {
        $where .= " and";
    }
    $where .= " t.status = '" . $s_status . "'";
}
if ($s_from != null && $s_from != '') {
    if (!$flag) {
        $flag = true;
    } else {
        $where .= " and";
    }
    $where .= " t.date >= CAST('" . $s_from . "' AS date)";
}
if ($s_to != null && $s_to != '') {
    if (!$flag) {
        $flag = true;
    } else {
        $where .= " and";
    }
    $where .= " t.date <= CAST('" . $s_to . "' AS date)";
}

$from = "from ticket t";
if ($s_worker != null && $s_worker != 'Any' && substr($s_worker, 0, 2) != '--') {
    $from .= ", ticket_worker tw, worker w";
    if (!$flag) {
        $flag = true;
    } else {
        $where .= " and";
    }
    $where .= " t.tid = tw.ticket and tw.worker = w.eid and w.name = '" . $s_worker . "'";
}
if ($flag) {
    $where = "where " . $where;
}

$sql = "SELECT t.tid, t.summary, t.date, t.status, t.vehicle " . $from . " " . $where;
$result = mysql_query($sql);
if (!$result) {
    echo mysql_error();
}
$num = mysql_numrows($result);
$arr = array();

for ($i = 0; $i < $num; $i++) {
    $tuple = array();
    $tuple[0] = mysql_result($result, $i, 'tid');
    $tuple[1] = mysql_result($result, $i, 'summary');
    $tuple[2] = mysql_result($result, $i, 'date');
    $tuple[3] = mysql_result($result, $i, 'status');
    $tuple[4] = mysql_result($result, $i, 'vehicle');
    $arr[$i] = $tuple;
}

echo json_encode($arr);
?>