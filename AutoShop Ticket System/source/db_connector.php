<?php

$dbHost = "localhost";
$dbUser = "autoshop";
$dbPass = "autoshop123";
$dbName = "auto_repair";

$_TICKET_TABLE = "Tickets";
$_TICKET_TICKETID_FIELD = "ticket_id";
$_TICKET_SUMMARY_FIELD = "summary";
$_TICKET_DESCRIPTION_FIELD = "description";
$_TICKET_STATUS_FIELD = "status";
$_TICKET_ESTIMATE_FIELD = "estimate";
$_TICKET_PROGRESS_FIELD = "progress";
$_TICKET_WORKHOURS_FIELD = "work_hours";
$_TICKET_VEHICLE_FIELD = "vehicle";
$_TICKET_WORKER_FIELD = "worker";
$_TICKET_MGR_FIELD = "manager";
$_TICKET_APPOINT_FIELD = "appointment";

$_MANAGER_TABLE = "Manager";
$_MANAGER_EMPID_FIELD = "emp_id";
$_MANAGER_NAME_FIELD = "name";
$_MANAGER_PHONE_FIELD = "phone";
$_MANAGER_ADDRESS_FIELD = "address";
$_MANAGER_EMAIL_FIELD = "email";

$_WORKER_TABLE = "worker";
$_WORKER_EMPID_FIELD = "emp_id";
$_WORKER_NAME_FIELD = "name";
$_WORKER_PHONE_FIELD = "phone";
$_WORKER_ADDRESS_FIELD = "address";
$_WORKER_EMAIL_FIELD = "email";

mysql_connect($dbHost, $dbUser, $dbPass) or die ("Cannot connect to host $dbHost with user $dbUser and the password provided.");
mysql_select_db($dbName) or die ("Database $dbName not found on host $dbHost");
?>
