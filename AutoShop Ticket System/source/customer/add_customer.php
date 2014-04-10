<!DOCTYPE html>
<html lang="en">
<head>
    <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
    <!-- Add custom CSS here -->
    <link href="../css/sb-admin.css" rel="stylesheet">
    <link rel="stylesheet" href="../font-awesome/css/font-awesome.min.css">
    <title>Auto Repair Shop</title>
</head>
<body>
<!-- Bootstrap core JavaScript -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="../js/bootstrap.js"></script>
<script src="../js/bootbox.min.js"></script>
<script type="text/javascript">
    function validate() {
        var name = document.getElementById("name").value;
        var phone = document.getElementById("phone").value;
        var address = document.getElementById("address").value;
        var email = document.getElementById("email").value;
        var user = document.getElementById("user").value;
        var password = document.getElementById("password").value;

        if (name == null || name == "" || phone == null || phone == ""
            || user == null || user == "" || password == null || password == "") {
            bootbox.alert("One or more mandatory fields missing!");
            return false;
        } else if (email != null && email != "" && email.indexOf('@') == -1) {
            bootbox.alert("Email is not valid!");
            return false;
        }
        if (!isValid(name) || !isValid(phone) || !isValid(address) ||
            !isValid(email) || !isValid(user) || !isValid(password)) {
            bootbox.alert("No special characters (~`!#$%\^&*+=\-\[\]\';\/{}|\":<>\?) are allowed on any of the fields !");
            return false;
        }
        return true;
    }

    // check strings for special characters
    function isValid(str){
        return !/[~`!#$%\^&*+=\[\]\\';\/{}|\\":<>\?]/g.test(str);
    }
</script>
<div id="wrapper">

    <?php
    include('../login/session_check.php');
    include('../db_connector.php');
    include('../constants.php');
    $activePage = 'customer';
    include('../sidebar.php');
    ?>

    <div id="page-wrapper">

        <div class="container">
            <h3>Fill Customer Information</h3>

            <form role='form' action='db_add_customer.php' method='post'>
                <div class='form-group'>
                    <label for='name'>Name</label>
                    <input type='text' class='form-control' id='name' name="formCustomerName"
                           placeholder='Enter Name'>
                </div>
                <div class='form-group'>
                    <label for='phone'>Phone Number</label>
                    <input type='text' class='form-control' id='phone' name="formCustomerPhone"
                           placeholder='Enter Phone Number'>
                </div>
                <div class='form-group'>
                    <label for='address'>Address</label>
                    <input type='text' class='form-control' id='address' name="formCustomerAddress"
                           placeholder='Enter Address'>
                </div>
                <div class='form-group'>
                    <label for='email'>Email</label>
                    <input type='email' class='form-control' id='email' name="formCustomerEmail"
                           placeholder='Enter Email'>
                </div>
                <div class='form-group'>
                    <label for='user'>Username</label>
                    <input type='text' class='form-control' id='user' name="formCustomerUser"
                           placeholder='Enter Username'>
                </div>
                <div class='form-group'>
                    <label for='password'>Password</label>
                    <input type='password' class='form-control' id='password'
                           name="formCustomerPassword"
                           placeholder='Enter Password'>
                </div>
                <button type="submit" onclick="return validate()" class="btn btn-primary">Add</button>
                <button type="button" onclick="history.go(-1);return true;" class="btn btn-primary">
                    Cancel
                </button>

            </form>
        </div>
    </div>
</div>

</body>
</html>