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

    function validate_delete(vin, custId) {
        bootbox.confirm("Delete vehicle : " + vin + "?", function(result) {
            if (result) {
                window.location = 'vehicle.php?delete=' + vin + '&custId=' + custId;
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
            <h3>Vehicles</h3>

            <?php
            $vinToDelete = htmlspecialchars($_GET["delete"]);
            if ($vinToDelete != null) {
                $del_query1 = "delete from vehicle where vin='$vinToDelete'";
                if (!mysql_query($del_query1)) {
                    echo "<p>There is a problem with the query: " . mysql_error() . "<p>";
                }
            }

            $customerId = htmlspecialchars($_GET["custId"]);
            if ($customerId != null) {
                $query = "select name from customer where cid=$customerId";
                $result = mysql_query($query);
            } else {
                // TODO : pop-up message
                header("Location: " . $server_host . "autoshop/customer/customer.php");
                exit();
            }

            $customerName = mysql_result($result, 0, 'name');
            echo "<p><a href='../customer/customer.php'>Customers</a> : $customerName</p>";

            $query = "select v.vin, v.year, v.mileage, v.next_due_date, v.next_due_work, m.brand, m.model, m.trim from vehicle v, vehicle_model m
    where v.model = m.mid and v.owner = $customerId";
            $result = mysql_query($query);

            $num = mysql_numrows($result);

            echo "<table class='table'><tr><th>Vin</th><th>Year</th><th>Mileage</th><th>Brand</th>
    <th>Model</th><th>Trim</th><th>Due Work</th><th>Due Date</th><th>Actions</th>";

            for ($i = 0; $i < $num; $i++) {
                echo "<tr>";
                $vin = mysql_result($result, $i, 'vin');
                $year = mysql_result($result, $i, 'year');
                $mileage = mysql_result($result, $i, 'mileage');
                $brand = mysql_result($result, $i, 'brand');
                $model = mysql_result($result, $i, 'model');
                $trim = mysql_result($result, $i, 'trim');
                $due_date = mysql_result($result, $i, 'next_due_date');
                $due_work = mysql_result($result, $i, 'next_due_work');
                echo "<td>$vin</td> <td>$year</td> <td>$mileage</td> <td>$brand</td>
    <td>$model</td><td>$trim</td><td>$due_work</td><td>$due_date</td><td>
    <a href='update_vehicle.php?vin=$vin&custId=$customerId'>edit</a>
    <a href='#' onclick=\"validate_delete('$vin', '$customerId')\">delete</a>
    </td>";
                echo "</tr>";
            }

            echo "</table>";
            echo "<p><a href='add_vehicle.php?custId=$customerId'>Add New Vehicle</a></p>"
            ?>
        </div>
    </div>
</div>

</body>
</html>