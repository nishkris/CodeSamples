<?php
include('../login/session_check.php');
include "../../db_connector.php";
include "../../constants.php";
$vehicleVIN = trim($_POST["formVIN"]);
$vehicleYear = trim($_POST["formYear"]);
$vehicleMileage = trim($_POST["formMileage"]);
$vehicleBrand = trim($_POST["select_brand"]);
$vehicleModel = trim($_POST["select_model"]);
$vehicleTrim = trim($_POST["select_trim"]);
$offendingChars = "~`!#$%\^&*+=\[\]\';\/{}|\":<>\?";

$customerId = htmlspecialchars($_SESSION['cid']);
$is_update = htmlspecialchars($_GET["update"]);
if ($vehicleVIN == null) {
    $vehicleVIN = htmlspecialchars($_GET["vin"]);
}

if ($vehicleVIN == "" || $vehicleYear == "" || $vehicleMileage == "") {
    echo "<p>VIN, Year and Mileage are mandatory</p>";
} else if (strpbrk($vehicleVIN, $offendingChars) || strpbrk($vehicleYear, $offendingChars) ||
    strpbrk($vehicleMileage, $offendingChars)
) {
    echo "<p>No special characters are allowed on VIN, Year and Mileage</p>";
} else {
    echo "<p>$vehicleBrand, $vehicleModel, $vehicleTrim</p>";
    // get vehicle model
    $query1 = "select mid from vehicle_model where brand='$vehicleBrand' and model='$vehicleModel' and trim='$vehicleTrim';";
    $result = mysql_query($query1);
    $mid = mysql_result($result, 0, 'mid');
    if ($is_update == 'true') {
        $query2 = "update vehicle set year='$vehicleYear', mileage='$vehicleMileage',
        owner='$customerId', model='$mid' where vin='$vehicleVIN';";
    } else {
        $query2 = "insert into vehicle (vin, year, mileage, owner, model) values('$vehicleVIN', '$vehicleYear',
        '$vehicleMileage', '$customerId', '$mid');";
    }
    if (mysql_query($query2)) {
        header("Location: " . $ext_context . "vehicle/vehicle.php");
        exit();
    } else {
        echo "<p>There is a problem with the query: " . mysql_error() . "<p>";
    }
}
?>