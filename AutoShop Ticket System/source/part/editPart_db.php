<?php
include('../login/session_check.php');
include "../db_connector.php";
include "../constants.php";
$updateBrand = trim ($_POST["formPartBrand"]);
$updateType = trim ($_POST["formPartType"]);
$updateDescription = trim ($_POST["formPartDescription"]);
$updatePrice = trim ($_POST["formPartPrice"]);
$updateIn_stock = trim ($_POST["formPartIn_stock"]);
$updateTotal_stock = trim ($_POST["formPartTotal_stock"]);

$offendingChars = "~`!#$%\^&*+=\[\]\';\/{}|\":<>\?";

$partToUpdate = htmlspecialchars($_GET["partID"]);

if ($updateBrand == "" || $updateType == "" || $updateDescription == "" || $updatePrice == "" || $updateIn_stock == "" || $updateTotal_stock == "") {
	echo "<p>You must enter everything</p>";
}
else if (strpbrk ($updateBrand, $offendingChars) || strpbrk ($updateType, $offendingChars)) {
	echo "<p>No special characters are allowed on either a brand or a type</p>";
}
else if($updatePrice<0){
	echo "<p>Price can't be negative!</p>";
}
else if ($updateTotal_stock < 0 || $updateIn_stock < 0 ){
	echo "<p>In_stock and Total_stock must be non-negative number.</p>";
}
else if ($updateTotal_stock < $updateIn_stock){
	echo "<p>Total_stock can be smaller than In_stock</p>";
}
else {
	$query = "update part set brand = '$updateBrand',type = '$updateType',description = '$updateDescription',price = '$updatePrice',in_stock = '$updateIn_stock',total_stock = '$updateTotal_stock' where pid = '$partToUpdate'";
	if (mysql_query ($query)) {
		header("Location: " . $server_host . "autoshop/part/showPart.php");
		exit();
	}
	else {
		echo "<p>There is a problem with the query: " . mysql_error() . "<p>";
	}
}
?>
