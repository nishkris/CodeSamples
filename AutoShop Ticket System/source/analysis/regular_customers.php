<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
    <title>Auto Repair Shop</title>
    <link href="../css/sb-admin.css" rel="stylesheet">
    <link rel="stylesheet" href="../font-awesome/css/font-awesome.min.css">

    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css"/>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script src="../js/bootbox.min.js"></script>
</head>
<body>
<!-- Bootstrap core JavaScript -->
<script src="../js/bootstrap.js"></script>
<script type="text/javascript">
    $(function () {
        $("#from_date").datepicker({ dateFormat:"yy-mm-dd" });
        $("#to_date").datepicker({ dateFormat:"yy-mm-dd" });
    });
</script>

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
                        <li class="active"><a href="../analysis/regular_customers.php">Regular Customers</a></li>  
                        <li><a href="../analysis/frequent_models.php">Frequent Models</a></li>   
                        <li><a href="../analysis/due_vehicles.php">Due Vehicles</a></li>   
                        <li><a href="../analysis/parts_usage.php">Parts Usage</a></li>   
                    </ul>  
                </div>  
            </div> 
            <h3>Regular Customers</h3>

            <?php
            $from = "";
            $to = "";
            $limit = "";
            if ($_POST["fromDate"] != null && $_POST["fromDate"] != "") {
                $from_date = $_POST["fromDate"];
                $from = " and t.date >= CAST('" . $_POST["fromDate"] . "' AS date) ";
            }
            if ($_POST["toDate"] != null && $_POST["toDate"] != "") {
                $to_date = $_POST["toDate"];
                $to = " and t.date <= CAST('" . $_POST["toDate"] . "' AS date) ";
            }
            if ($_POST["rowCount"] != null && $_POST["rowCount"] != "") {
                $count = $_POST["rowCount"];
                $limit = " limit " . $_POST["rowCount"];
            }
            ?>

            <form class='form-horizontal' role='form' action='regular_customers.php' method='post'>
                <table style="width: 40%">
                    <tr style="padding: 5px">
                        <td style="padding: 5px"><input style="width: 100px" class='form-control'
                                                        <?php if ($from != "") { echo "value='$from_date'";} ?>
                                                        type="text" id="from_date" name="fromDate"
                                                        placeholder="From date"/></td>
                        <td style="padding: 5px"><input style="width: 100px" class='form-control'
                                                        <?php if ($to != "") { echo "value='$to_date'";} ?>
                                                        type="text" id="to_date" name="toDate"
                                                        placeholder="To date"/></td>
                        <td><input style="width: 120px" class='form-control' type='number' name="rowCount"
                                   <?php if ($limit != "") { echo "value='$count'";} ?>
                                   id='num_results' min='0' placeholder='Max results'</td>
                        <td style="padding: 5px">
                            <button type="submit" class="btn btn-primary">
                                Search
                            </button>
                        </td>
                    </tr>
                </table>
                <br>

                <?php
                $query = "select c.name, c.phone, c.address, c.email, count(*) as count from ticket t, vehicle v, customer c
                where t.vehicle=v.vin and v.owner=c.cid " . $from . $to . " group by c.cid,c.name order by count DESC " . $limit . ";";
                $result = mysql_query($query);
                if (! $result) {
                    echo "<p>There is a problem with the query: " . mysql_error () . "<p>";
                    exit ();
                }

                $num = mysql_numrows($result);

                echo "<table class='table'><tr><th>Name</th><th>Phone</th><th>Address</th><th>Email</th><th align='center'>Number of Repairs</th>";

                for ($i = 0; $i < $num; $i++) {
                    echo "<tr>";
                    $customerName = mysql_result($result, $i, 'name');
                    $customerPhone = mysql_result($result, $i, 'phone');
                    $customerAddress = mysql_result($result, $i, 'address');
                    $customerEmail = mysql_result($result, $i, 'email');
                    $customerCount = mysql_result($result, $i, 'count');
                    echo "<td>$customerName</td> <td>$customerPhone</td> <td>$customerAddress</td>
    <td>$customerEmail</td><td align='center'>$customerCount</td>";
                    echo "</tr>";
                }

                echo "</table>";
                ?>
            </form>

        </div>
    </div>
</div>

</body>
</html>