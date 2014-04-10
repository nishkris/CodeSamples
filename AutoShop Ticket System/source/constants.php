<?php
//$server_host = "http://www.cs.indiana.edu/cgi-pub/zsitu/";
$server_host = "http://localhost/";
$context = $server_host."autoshop/";
$ext_context = $server_host."autoshop/ext/";

$role_manager = "manager";
$role_worker = "worker";
$role_customer = "customer";

$app_times = array();
$app_times[0] = '08:00';
$app_times[1] = '09:00';
$app_times[2] = '10:00';
$app_times[3] = '11:00';
$app_times[4] = '13:00';
$app_times[5] = '14:00';
$app_times[6] = '15:00';
$app_times[7] = '16:00';

$ticket_status_open = "Open";
$ticket_status_prog = "In Progress";
$ticket_status_wait = "Waiting on Customer";
$ticket_status_approved = "Approved";
$ticket_status_resolved = "Resolved";
$ticket_status_closed = "Closed";
?>