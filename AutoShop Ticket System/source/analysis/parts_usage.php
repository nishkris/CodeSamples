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

<div id="wrapper">

    <?php
    include('../login/session_check.php');
    include('../db_connector.php');
    include('../constants.php');
    $activePage = 'analysis';
    include('../sidebar.php');
    ?>

    <div id="page-wrapper">

        <div class="container">
            <div class="row">  
                <div class="span6">  
                    <ul class="nav nav-tabs">    
                        <li><a href="../analysis/regular_customers.php">Regular Customers</a></li>  
                        <li><a href="../analysis/frequent_models.php">Frequent Models</a></li>   
                        <li><a href="../analysis/due_vehicles.php">Due Vehicles</a></li>   
                        <li class="active"><a href="../analysis/parts_usage.php">Parts Usage</a></li>   
                    </ul>  
                </div>  
            </div> 
            <h3>Usage of Parts</h3>
            <?php
            $query = "select *, (total_stock - in_stock) as used from part order by used DESC;";
            $result = mysql_query($query);
            if (! $result) {
                echo "<p>There is a problem with the query: " . mysql_error () . "<p>";
                exit ();
            }

            $num = mysql_numrows($result);

            echo "<table class='table'><tr><th>Brand</th><th>Type</th><th>Description</th><th>Price</th>
            <th>Total Stock</th><th>In Stock</th><th>Used</th>";

            for ($i = 0; $i < $num; $i++) {
                echo "<tr>";
                $brand = mysql_result($result, $i, 'brand');
                $type = mysql_result($result, $i, 'type');
                $description = mysql_result($result, $i, 'description');
                $price = mysql_result($result, $i, 'price');
                $tot = mysql_result($result, $i, 'total_stock');
                $in = mysql_result($result, $i, 'in_stock');
                $used = mysql_result($result, $i, 'used');
                echo "<td>$brand</td> <td>$type</td> <td>$description</td>
    <td>$price</td><td>$tot</td><td>$in</td><td>$used</td>";
                echo "</tr>";
            }

            echo "</table>";
            ?>
        </div>
    </div>
</div>

</body>
</html>