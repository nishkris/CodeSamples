<?php
include('../login/session_check.php');
include "../db_connector.php";
include "../constants.php";
$customerName = trim($_POST["formCustomerName"]);
$customerPhone = trim($_POST["formCustomerPhone"]);
$customerAddress = trim($_POST["formCustomerAddress"]);
$customerEmail = trim($_POST["formCustomerEmail"]);
$customerUser = trim($_POST["formCustomerUser"]);
$customerPw = trim($_POST["formCustomerPassword"]);
$offendingChars = "~`!#$%\^&*+=\[\]\';\/{}|\":<>\?";

$hashedPassword = crypt($customerPw);

$is_update = htmlspecialchars($_GET["update"]);
$customerToUpdate = htmlspecialchars($_GET["cid"]);

if ($customerName == "" || $customerPhone == "") {
    echo "<p>Name and Phone Number are mandatory</p>";
} else if (strpbrk($customerName, $offendingChars) ||
    strpbrk($customerPhone, $offendingChars)
) {
    echo "<p>No special characters are allowed on Employee ID, Name and Phone Number</p>";
} else {
    if ($is_update == 'true') {
//        TODO : check whether we need to update username and password here
        $query = "update customer set name='$customerName', phone='$customerPhone',
        address='$customerAddress', email='$customerEmail' where cid='$customerToUpdate';";
    } else {
//        TODO : hash password
        $query = "insert into customer values(0, '$customerName', '$customerPhone',
        '$customerAddress', '$customerEmail', '$customerUser', '$hashedPassword');";
    }
    if (mysql_query($query)) {
        header("Location: " . $server_host . "autoshop/customer/customer.php");
        exit();
    } else {
        echo "<p>There is a problem with the query: " . mysql_error() . "<p>";
    }
}
?>