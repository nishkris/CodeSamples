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
                <li><a href="../analysis/regular_customers.php">Regular Customers</a></li>  
                <li><a href="../analysis/frequent_models.php">Frequent Models</a></li>   
                <li class="active"><a href="../analysis/due_vehicles.php">Due Vehicles</a></li>   
                <li><a href="../analysis/parts_usage.php">Parts Usage</a></li>   
                </ul>  
    </div>  
    </div> 
            <h3>Due Vehicles</h3>
            <?php
            $from = "";
            $to = "";
            if ($_POST["fromDate"] != null && $_POST["fromDate"] != "") {
                $from_date = $_POST["fromDate"];
                $from = " and v.next_due_date >= CAST('" . $_POST["fromDate"] . "' AS date) ";
            }
            if ($_POST["toDate"] != null && $_POST["toDate"] != "") {
                $to_date = $_POST["toDate"];
                $to = " and v.next_due_date <= CAST('" . $_POST["toDate"] . "' AS date) ";
            }
            ?>

            <form class='form-horizontal' role='form' action='due_vehicles.php' method='post'>
                <table style="width: 30%">
                    <tr style="padding: 5px">
                        <td style="padding: 5px"><input style="width: 100px" class='form-control'
                            <?php if ($from != "") { echo "value='$from_date'";} ?>
                                                        type="text" id="from_date" name="fromDate"
                                                        placeholder="From date"/></td>
                        <td style="padding: 5px"><input style="width: 100px" class='form-control'
                            <?php if ($to != "") { echo "value='$to_date'";} ?>
                                                        type="text" id="to_date" name="toDate"
                                                        placeholder="To date"/></td>
                        <td style="padding: 5px">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </td>
                    </tr>
                </table>
                <br>

                <?php
                $query = "select v.vin, v.next_due_date, v.next_due_work, c.name from vehicle v, customer c where v.owner=c.cid and v.next_due_date >= curdate() " . $from . $to . " order by v.next_due_date;";
                $result = mysql_query($query);
                if (! $result) {
                    echo "<p>There is a problem with the query: " . mysql_error () . "<p>";
                    exit ();
                }

                $num = mysql_numrows($result);

                echo "<table class='table'><tr><th>VIN</th><th>Next Due Work</th><th>Next Due Date</th><th>Owner</th>";

                for ($i = 0; $i < $num; $i++) {
                    echo "<tr>";
                    $vin = mysql_result($result, $i, 'vin');
                    $due_work = mysql_result($result, $i, 'next_due_work');
                    $due_date = mysql_result($result, $i, 'next_due_date');
                    $owner = mysql_result($result, $i, 'name');
                    echo "<td>$vin</td> <td>$due_work</td> <td>$due_date</td>
    <td>$owner</td><td align='center'>$vehicleCount</td>";
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