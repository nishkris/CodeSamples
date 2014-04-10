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
    $username = $_SESSION['username'];

    $errors = array();
    $message = "";

    if (isset($_POST['submit'])) {
        // form was submitted
        $current_pwd = trim($_POST["currentPassword"]);
        $new_pwd = trim($_POST["newPassword"]);
        $new_pwd2 = trim($_POST["confirmPassword"]);

        // Validations
        $fields_required = array("currentPassword", "newPassword","confirmPassword");
        foreach($fields_required as $field) {
            $value = trim($_POST[$field]);
            if (!has_presence($value)) {
                $errors[$field] = ucfirst($field) . " can't be blank";
            }
        }
        
        
        // $fields_with_max_lengths = array("currentPassword" => 8,"newPassword" => 8,"confirmPassword" => 8);
        // validate_max_lengths($fields_with_max_lengths);
        
        if(!validate_password($new_pwd)){
            $errors['newPassword'] = ucfirst('newPassword') . " needs to be 6-15 long with at least one digit and one letter";
        }
        if($new_pwd!=$new_pwd2){
            $errors['confirmPassword'] = ucfirst('confirmPassword') . " doesn't match new password";
        }
        
        if (empty($errors)) {
            // try to update

            if (check_validation($username,$current_pwd)) {
                // successful login
                $hashed_new_pwd = crypt($new_pwd);
                // $hashed_new_ped = crypt($new_pwd,$hashed_new_ped);
                $query = "update internal_user set password='$hashed_new_pwd' where username='$username';";
                if (mysql_query($query)) {
                } else {

                    echo "<p>There is a problem with the query: " . mysql_error() . "<p>";
                }
                redirect_to("profile.php");

            } else {
                $message = "Your password is incorrect.";
            }

        }

    } else {
        $message = "Please edit your information.";
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Auto Repair Password</title>
    <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
    <!-- Add custom CSS here -->
    <link href="../css/sb-admin.css" rel="stylesheet">
    <link rel="stylesheet" href="../font-awesome/css/font-awesome.min.css">
    <head>Change Password</head>
</head>
<body>


<div id="wrapper">

    <?php require_once('../sidebar.php');?>

    <div id="page-wrapper">

        <div class="container">

        <h2>Change Password</h2>
        <hr>

            <?php
            $user_id = $_SESSION['username'];
            echo "
            <div class='col-lg-6'>
                <div class='well'>
                    <form class='bs-example form-horizontal' id='myform'  action='change_pwd.php' method='post' >
                        <fieldset>
                            <legend>Login ID: $user_id</legend>

                            <div class='form-group'>
                                <label for='inputCurrentPassword' class='col-lg-4 control-label'>Current Password</label>
                                <div class='col-lg-8'>
                                    <input type='password' class='form-control' id='curr_pwd' name='currentPassword'>
                                </div>
                            </div>

                            <div class='form-group'>
                                <label for='inputNewPassword' class='col-lg-4 control-label'>New Password</label>
                                <div class='col-lg-8'>
                                    <input type='password' class='form-control' id='new_pwd' name='newPassword'>
                                </div>
                            </div>

                            <div class='form-group'>
                                <label for='inputConfirmPassword' class='col-lg-4 control-label'>Confirm Password</label>
                                <div class='col-lg-8'>
                                    <input type='password' class='form-control' id='new_pwd2' name='confirmPassword'>
                                </div>
                            </div>

                            <div class='form-group'>
                                <div class='col-lg-10 col-lg-offset-2'>
                                    <button type='button' onclick='history.go(-1);return true;' class='btn btn-default'>
                                        Cancel
                                    </button>
                                    <button type='submit' name='submit' class='btn btn-primary'>Submit
                                    </button>
                                </div>
                            </div>

                        </fieldset>
                    </form>";?>

                    <?php echo $message; ?><br />
                    <?php echo form_errors($errors); ?>
            <?php
            echo"</div></div>";
              ?>
        </div>

    </div>
</div>

</body>
</html>