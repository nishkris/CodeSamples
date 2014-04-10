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

        if (name == null || name == "" || phone == null || phone == "") {
            bootbox.alert("One or more mandatory fields missing!");
            return false;
        } else if (email != null && email != "" && email.indexOf('@') == -1) {
            bootbox.alert("Email is not valid!");
            return false;
        }
        if (!isValid(name) || !isValid(phone) || !isValid(address) || !isValid(email)) {
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
            <h3>Update Customer Information</h3>

            <?php
            $customerToUpdate = htmlspecialchars($_GET["cid"]);
            if ($customerToUpdate != null) {
                $query = "select * from customer where cid=$customerToUpdate";
                $result = mysql_query($query);

                $num = mysql_numrows($result);
                if ($num > 0) {
                    $customerName = mysql_result($result, 0, 'name');
                    $customerPhone = mysql_result($result, 0, 'phone');
                    $customerAddress = mysql_result($result, 0, 'address');
                    $customerEmail = mysql_result($result, 0, 'email');

                    echo "
<form role='form' action='db_add_customer.php?update=true&cid=$customerToUpdate' method='post' onSubmit='return validate()'>
        <div class='form-group'>
            <label for='name'>Name</label>
            <input type='text' class='form-control' id='name' name='formCustomerName'
                   value='$customerName'>
        </div>
        <div class='form-group'>
            <label for='phone'>Phone Number</label>
            <input type='text' class='form-control' id='phone' name='formCustomerPhone'
                   value='$customerPhone'>
        </div>
        <div class='form-group'>
            <label for='address'>Address</label>
            <input type='text' class='form-control' id='address' name='formCustomerAddress'
                   value='$customerAddress'>
        </div>
        <div class='form-group'>
            <label for='email'>Email</label>
            <input type='email' class='form-control' id='email' name='formCustomerEmail'
                   value='$customerEmail'>
        </div>
        <button type='submit'  class='btn btn-primary'>Update</button>
        <button type='button' onclick='history.go(-1);return true;' class='btn btn-primary'>Cancel</button>
</form>
";

                }
            } else {
                // if the employee id is null, return to customer list
                header("Location: " . $server_host . "autoshop/customer/customer.php");
                exit();
            }

            ?>
        </div>
    </div>
</div>

</body>
</html>