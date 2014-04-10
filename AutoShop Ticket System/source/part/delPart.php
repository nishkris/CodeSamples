<?php
include('../login/session_check.php');
include "../db_connector.php";
include "../constants.php";
$del_partID = $_GET["delete"];
$query = "delete from part where pid = $del_partID";
if (mysql_query ($query)) {
	header("Location: " . $server_host . "autoshop/part/showPart.php");
	exit();
}
else {
	echo "<p>There is a problem with the query: " . mysql_error() . "<p>";
}
?>

