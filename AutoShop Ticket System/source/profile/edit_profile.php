<?php

/*****************************************************************************************
 ** http://www.lynda.com/MySQL-tutorials/Single-page-form-validations/119003/136992-4.html
 * Credit for  Kevin Skoglund, reference his course  
 * PHP with MySQL Essential Training with Kevin Skoglund 
 * cite his code from exercise file
 *****************************************************************************************/

    require_once('../login/session_check.php');
    require_once('../db_connector.php');
    require_once("included_functions.php");
    require_once("validation_functions.php");

    $eid = $_SESSION['eid'];
    $role = $_SESSION['role'];

    $query = "select * from $role where eid='$eid';";
    $result = mysql_query($query);  
    if(mysql_numrows($result)==1){
        $user_name = mysql_result($result, 0, 'name');
        $user_phone = mysql_result($result, 0, 'phone');
        $user_address = mysql_result($result, 0, 'address');
        $user_email = mysql_result($result, 0, 'email');
    } else {
        // if the employee id is null, return to ticket list
        redirect_to("profile.php");
    }


    $errors = array();
    $message = "";
    
    if (isset($_POST['submit'])) {
        // form was submitted
        $userName = trim($_POST["formName"]);
        $userPhone = trim($_POST["formPhone"]);
        $userAddress = trim($_POST["formAddress"]);
        $userEmail = trim($_POST["formEmail"]);

        // Validations
        $fields_required = array("formName", "formPhone","formPhone","formAddress","formEmail");
        foreach($fields_required as $field) {
            $value = trim($_POST[$field]);
            if (!has_presence($value)) {
                $errors[$field] = ucfirst($field) . " can't be blank";
            }
        }
        if(!validate_name($userName)){
            $errors['formName'] = ucfirst('formName') . " is not valid";
        }
        if(!validate_phone($userPhone)){
            $errors['formPhone'] = ucfirst('formPhone') . " needs to be 10 digits or in the form of (xxx)-xxx-xxxx";
        }
        if(!validate_address($userAddress)){
            $errors['formAddress'] = ucfirst('formAddress') . " is not valid";
        }
        if(!validate_email($userEmail)){
            $errors['formEmail'] = ucfirst('formEmail') . " is not valid";
        }

        if (empty($errors)) {
            // try to update

            $query = "update $role set name='$userName', phone='$userPhone',
                            address='$userAddress', email='$userEmail' where eid='$eid';"; 

            if (mysql_query($query)) {
            } else {
                echo "<p>There is a problem with the query: " . mysql_error() . "<p>";
            }

            redirect_to("profile.php");
        }

    } else {
        $message = "Please edit your information.";
    }


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
    <!-- Add custom CSS here -->
    <link href="../css/sb-admin.css" rel="stylesheet">
    <link rel="stylesheet" href="../font-awesome/css/font-awesome.min.css">    <title>Auto Repair Profile</title>
    <title>Edit Profile</title>
</head>
<body>

<div id="wrapper">
    <?php require_once('../sidebar.php');?>

    <div id="page-wrapper">

        <div class="container">

        <h2>Edit your profile</h2>

            <hr>

            <?php
            echo "
            <div class='col-lg-6'>
                <div class='well'>
                    <form class='bs-example form-horizontal' action='edit_profile.php' method='post'>
                        <fieldset>
                            <legend>Your Information</legend>
                            <div class='form-group'>
                                <label for='inputName' class='col-lg-2 control-label'>Name</label>
                                <div class='col-lg-10'>
                                    <input type='text' class='form-control' id='inputName'  name='formName' value='$user_name'>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label for='inputEmail' class='col-lg-2 control-label'>Email</label>
                                <div class='col-lg-10'>
                                    <input type='text' class='form-control' id='inputEmail' name='formEmail' value='$user_email'>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label for='inputPhone' class='col-lg-2 control-label'>Phone</label>
                                <div class='col-lg-10'>
                                    <input type='text' class='form-control' id='inputPhone' name='formPhone' value='$user_phone'>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label for='inputAddress' class='col-lg-2 control-label'>Address</label>
                                <div class='col-lg-10'>
                                    <input type='text' class='form-control' id='inputAddress' name='formAddress' value='$user_address'>
                                </div>
                            </div>

                            <div class='form-group'>
                                <div class='col-lg-10 col-lg-offset-2'>
                                    <button type='button' onclick='history.go(-1);return true;' class='btn btn-default'>
                                        Cancel
                                    </button>
                                    <button type='submit' name='submit' class='btn btn-primary'>Submit</button>
                                </div>
                            </div>
                        </fieldset>
                    </form>";?>

                    <?php echo $message; ?><br />
                    <?php echo form_errors($errors); ?>

            <?php echo"
                </div>
            </div>
            ";
              ?>

        </div>
        </div>
</div>

</body>
</html>