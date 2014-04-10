<?php

/*****************************************************************************************
 ** http://www.lynda.com/MySQL-tutorials/Single-page-form-validations/119003/136992-4.html
 * Credit for  Kevin Skoglund, reference his course  
 * PHP with MySQL Essential Training with Kevin Skoglund 
 * cite his code from exercise file
 *****************************************************************************************/

    require_once('../login/session_check.php');
    require_once('../..//db_connector.php');
    require_once("included_functions.php");
    require_once("validation_functions.php");

    $cid = $_SESSION['cid'];

    $query = "select * from customer where cid='$cid';";
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
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <link href="../ui/bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
    <link href="../ui/bootstrap/css/sb-admin.css" rel="stylesheet">
    <link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css">
    <title>Profile</title>
</head>
<body>

<div id="wrapper">
    <?php require_once('../sidebar.php');?>
    <div id="page-wrapper">

        <div class="container">

            <h2>Welcome to your profile</h2>

            <hr>


            <div class="col-xs-12 col-sm-5">
                <div class="col-6 col-sm-6 col-lg-12">

                    <?php

                    echo "

                        <form class='form-horizontal' role='form'>

                         <div class='col-lg-12'>
                         <div class='well'>
                            <form class='bs-example form-horizontal'>
                            <fieldset>

                            <h3>$user_name's Info</h3>


                            <div class='form-group'>
                                <label class='col-md-4 control-label'>Phone <span class='glyphicon glyphicon-earphone'></span></label>
                                <div class='col-md-7'>
                                    <p class='form-control-static'>$user_phone</p>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label for='comment' class='col-md-4 control-label'>Address <span class='glyphicon glyphicon-map-marker'></span></label>
                                <div class='col-md-7'>
                                    <p class='form-control-static'>$user_address</p>
                                </div>
                            </div>

                            <div class='form-group'>
                                <label for='comment' class='col-md-4 control-label'>Email <span class='glyphicon glyphicon-envelope'></span></label>
                                <div class='col-md-7'>
                                    <p class='form-control-static'>$user_email</p>
                                </div>
                            </div>


                            <div class='btn-group btn-group-justified'>
                                <a href='edit_profile.php' class='btn btn-default'>Edit Profile</a>
                                <a href='change_pwd.php' class='btn btn-default'>Change Password</a>
                            </div>

                                </fieldset>
                            </form>
                        </div>
                    </div>


                    ";

                    ?>



                </div>
            </div>


        </div>


        </div>
</div>

</body>
</html>