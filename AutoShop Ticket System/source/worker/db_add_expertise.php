<?php
include('../login/session_check.php');
include "../db_connector.php";
include "../constants.php";

$workerExp = $_POST["formExp"];

$workerId = htmlspecialchars($_GET["eid"]);

// remove existing expertise
$del_exp = "delete from expertise where worker='$workerId';";
if (!mysql_query($del_exp)) {
    echo "<p>There is a problem with the query: " . mysql_error() . "<p>";
    exit ();
}

foreach ($workerExp as &$exp) {
    $pieces = explode(" / ", $exp);
    // get model id
    $mid_query = "select mid from vehicle_model where brand='$pieces[0]' and model='$pieces[1]' and trim='$pieces[2]';";
    $mid_result = mysql_query($mid_query);
    if (!$mid_result) {
        echo "<p>There is a problem with the query: " . mysql_error() . "<p>";
        exit ();
    }
    $mid = mysql_result($mid_result, 0, 'mid');

    // insert expertise
    $exp_query = "insert into expertise values($mid, '$workerId');";
    if (!mysql_query($exp_query)) {
        echo "<p>There is a problem with the query: " . mysql_error() . "</p>";
        exit();
    }
}

if (is_manager()) {
    header("Location: " . $server_host . "autoshop/worker/worker.php");
} else if (is_worker()) {
    header("Location: " . $server_host . "autoshop/worker/expertise.php?eid=" . $_SESSION['eid']);
}

?>