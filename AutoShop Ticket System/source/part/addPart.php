<?php
include('../login/session_check.php');
include "../db_connector.php";
include "../constants.php";
$newBrand = trim ($_POST["formPartBrand"]);
$newType = trim ($_POST["formPartType"]);
$newDescription = trim ($_POST["formPartDescription"]);
$newPrice = trim ($_POST["formPartPrice"]);
$newInitial_stock = trim ($_POST["formPartInitial_stock"]);

$offendingChars = "~`!#$%\^&*+=\[\]\';\/{}|\":<>\?";

if ($newBrand == "" || $newType == "" || $newDescription == "" || $newPrice == "" || $newInitial_stock == "") {
	echo "<p>You must enter everything</p>";
}
else if (strpbrk ($newBrand, $offendingChars) || strpbrk ($newType, $offendingChars)) {
	echo "<p>No special characters are allowed on either a brand or a type</p>";
}
else if($newPrice<0){
	echo "<p>Price can't be negative!</p>";
}
else if ($newInitial_stock < 0){
	echo "<p>In_stock must be non-negative number.</p>";
}
else {
	$query = "insert into part values(0, '$newBrand', '$newPrice', '$newDescription', '$newType', '$newInitial_stock', '$newInitial_stock');";
	if (mysql_query ($query)) {
		header("Location: " . $server_host . "autoshop/part/showPart.php");
		exit();
	}
	else {
		echo "<p>There is a problem with the query: " . mysql_error() . "<p>";
	}
}
?>
