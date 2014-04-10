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

    function validate_delete(name, custId) {
        bootbox.confirm("Delete customer : " + name + "?", function(result) {
            if (result) {
                window.location = 'customer.php?delete=' + custId;
            }
        });
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

            <table style="width: 100%">
                <tr>
                    <td><h3>Customers</h3></td>
                    <td align="right"><a href="add_customer.php">Add New Customer</a></td>
                </tr>
            </table>

            <?php
            $customerToDelete = htmlspecialchars($_GET["delete"]);
            if ($customerToDelete != null) {
                $del_query = "delete from customer where cid=$customerToDelete";
                mysql_query($del_query);
            }

            $query = "select * from customer order by name";
            $result = mysql_query($query);

            $num = mysql_numrows($result);

            echo "<table class='table'><tr><th>Customer ID</th><th>Name</th>
<th>Phone</th><th>Address</th><th>Email</th><th>Actions</th>";

            for ($i = 0; $i < $num; $i++) {
                echo "<tr>";
                $customerId = mysql_result($result, $i, 'cid');
                $customerName = mysql_result($result, $i, 'name');
                $customerPhone = mysql_result($result, $i, 'phone');
                $customerAddress = mysql_result($result, $i, 'address');
                $customerEmail = mysql_result($result, $i, 'email');
                echo "<td>$customerId</td> <td>$customerName</td> <td>$customerPhone</td> <td>$customerAddress</td>
    <td>$customerEmail</td> <th><a href='update_customer.php?cid=$customerId' >edit</a>
    <a href='#' onclick=\"validate_delete('$customerName', '$customerId')\" >delete</a>
    <a href='../vehicle/vehicle.php?custId=$customerId' >vehicles</a>

    </th>";
                echo "</tr>";
            }

            echo "</table>";
            ?>
        </div>
    </div>
</div>

</body>
</html>