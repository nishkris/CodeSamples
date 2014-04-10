<?php
include('../login/session_check.php');
include "../db_connector.php";
include "../constants.php";

$vbrand = trim($_POST["formBrand"]);
$vmodel = trim($_POST["formModel"]);
$vtrim = trim($_POST["formTrim"]);
$offendingChars = "~`!#$%\^&*+=\[\]\';\/{}|\":<>\?";

$is_update = htmlspecialchars($_GET["update"]);
$modelToUpdate = htmlspecialchars($_GET["mid"]);

if ($vbrand == "" || $vmodel == "" || $vtrim == "") {
    echo "<p>Brand, Model and Trim are mandatory</p>";
} else if (strpbrk($vbrand, $offendingChars) || strpbrk($vmodel, $offendingChars) || strpbrk($vtrim, $offendingChars)
) {
    echo "<p>No special characters are allowed on Brand, Model or Trim</p>";
} else {
    if ($is_update == 'true') {
        $query = "update vehicle_model set brand='$vbrand', model='$vmodel',
        trim='$vtrim' where mid='$modelToUpdate';";
    } else {
        $query = "insert into vehicle_model values(0, '$vbrand', '$vmodel', '$vtrim');";
    }
    if (mysql_query($query)) {
        header("Location: " . $server_host . "autoshop/vehicle/vehicle_model.php");
        exit();
    } else {
        echo "<p>There is a problem with the query: " . mysql_error() . "<p>";
    }
}
?>