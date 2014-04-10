<?php
include('../login/session_check.php');
include "../db_connector.php";
include "../constants.php";

$ticketSummary = trim($_POST["formTicketSummary"]);
$ticketDescription = trim($_POST["formTicketDescription"]);
$ticketEstimate = trim($_POST["formTicketEstimate"]);
$ticketVehicle = trim($_POST["select_vin"]);
$ticketWorkers = $_POST["select_workers"];
$ticketAppointment = trim($_POST["select_app"]);
$ticketManager = $_SESSION['eid'];
$offendingChars = "~`!#$%\^&*+=\[\]\';\/{}|\":<>\?";

$is_update = htmlspecialchars($_GET["update"]);
$tid = htmlspecialchars($_GET["tid"]);

if ($ticketSummary == ""  || $ticketVehicle == "" || $ticketWorkers[0] == "") {
    echo "<p>Ticket Summary, VIN and Workers are mandatory</p>";
} else if (strpbrk($ticketEstimate, $offendingChars) ||  strpbrk($ticketVehicle, $offendingChars)) {
    echo "<p>No special characters are allowed on Estimate or VIN Number</p>";
} else {
    if ($ticketAppointment != "") {
        $app_data = explode("/", $ticketAppointment);
        $date = trim($app_data[0]);
        $time = trim($app_data[1]);
        $app_query = "select aid from appointment where date = '$date' and time = '$time' and vehicle = '$ticketVehicle';";
        $result = mysql_query($app_query);
        // error handling
        if (!$result) {
            echo "<p>There is a problem with the query: " . mysql_error() . "<p>";
            exit();
        }
        $aid = mysql_result($result, 0, 'aid');
    } else {
        $aid = 'NULL';
    }

    if ($is_update == 'true' && $tid != null) {
        $query = "update ticket set summary='$ticketSummary', description='$ticketDescription',
        estimate='$ticketEstimate', vehicle='$ticketVehicle', appointment='$aid' where tid=$tid;";
        // error handling
        if (!mysql_query($query)) {
            echo $query;
            echo "<p>There is a problem with the query: " . mysql_error() . "<p>";
            exit();
        }
        // remove existing workers
        $del_workers = "delete from ticket_worker where ticket=$tid;";
        // error handling
        if (!mysql_query($del_workers)) {
            echo "<p>There is a problem with the query: " . mysql_error() . "<p>";
            exit();
        }
    } else {
        $tkt_query = "insert into ticket values(0, '$ticketSummary',
        '$ticketDescription', now(),'$ticketEstimate',
        '$ticket_status_open', 0, 0.0,
        '$ticketVehicle', '$ticketManager', '$aid');";
        // error handling
        if (!mysql_query($tkt_query)) {
            echo "<p>There is a problem with the query: " . mysql_error() . "<p>";
            exit();
        }
        $tid = mysql_insert_id();
    }

    // add workers
    foreach ($ticketWorkers as &$worker) {
        // get worker eid
        $worker_query = "select eid from worker where name='$worker';";
        $result = mysql_query($worker_query);
        // error handling
        if (!$result) {
            echo "<p>There is a problem with the query: " . mysql_error() . "<p>";
            exit();
        }
        $eid = mysql_result($result, 0, 'eid');

        // insert worker for ticket
        $tkt_worker_query = "insert into ticket_worker values('$tid', '$eid');";
        if (!mysql_query($tkt_worker_query)) {
            echo "<p>There is a problem with the query: " . mysql_error() . "<p>";
            exit();
        }
    }

    header("Location: " . $server_host . "autoshop/ticket/ticket.php");
}
?>

