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

    function validate_delete(pId) {
        bootbox.confirm("Delete part?", function(result) {
            if (result) {
                window.location = 'delPart.php?delete=' + pId;
            }
        });
    }

</script>

<div id="wrapper">

    <?php
    include('../login/session_check.php');
    include('../db_connector.php');
    include('../constants.php');
    $activePage = 'part';
    include('../sidebar.php');
    ?>

    <div id="page-wrapper">
        <div class="container">
            <table style="width: 100%">
                <tr>
                    <td><h3>Available Parts</h3></td>
                    <td align="right"><a href="part.php">Add New Part</a></td>
                </tr>
            </table>

            <?php
            $query = "select * from part";
            $result = mysql_query($query);

            $num = mysql_numrows($result);

            echo "<table class='table'><tr><th>Part ID</th><th>Brand</th><th>Type</th><th>Description</th>
<th>Price</th><th>In stock</th><th>Total stock</th><th>Actions</th>";

            for ($i = 0; $i < $num; $i++) {
                echo "<tr>";
                $partID = mysql_result($result, $i, 'pid');
                $brand = mysql_result($result, $i, 'brand');
                $type = mysql_result($result, $i, 'type');
                $description = mysql_result($result, $i, 'description');
                $price = mysql_result($result, $i, 'price');
                $in_stock = mysql_result($result, $i, 'in_stock');
                $total_stock = mysql_result($result, $i, 'total_stock');
                echo "<td>$partID</td> <td>$brand</td> <td>$type</td> <td>$description</td>
        <td>$price</td> <td>$in_stock</td> <td>$total_stock</td>
	<th><a href='partEdit.php?partID=$partID' >edit</a>
    	<a href='#' onclick=\"validate_delete('$partID')\">delete</a></th>";

                echo "</tr>";
            }
            echo "</table>";
            ?>
        </div>
    </div>
</div>

</body>
</html>

