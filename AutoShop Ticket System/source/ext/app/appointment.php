<!DOCTYPE html>
<html lang="en">
<head>
    <link href="../ui/bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
    <link href="../ui/bootstrap/css/sb-admin.css" rel="stylesheet">
    <link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css">
    <title>Auto Repair Shop</title>
</head>
<body>
<!-- Bootstrap core JavaScript -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="../../js/bootstrap.js"></script>
<script src="../../js/bootbox.min.js"></script>

<script type="text/javascript">

    function validate_delete(aid, date, time) {
        bootbox.confirm("Delete Appointment : " + date + " / " + time + "?", function(result) {
            if (result) {
                window.location = 'appointment.php?delete=' + aid;
            }
        });
    }

</script>

<div id="wrapper">

    <?php
    include('../login/session_check.php');
    include('../../db_connector.php');
    include('../../constants.php');
    $activePage = 'app';
    include('../sidebar.php');
    ?>

    <div id="page-wrapper">

        <div class="container">
            <table style="width: 100%">
                <tr>
                    <td><h3>My Appointments</h3></td>
                    <td align="right"><a href="add_app.php">New Appointment</a></td>
                </tr>
            </table>

            <?php

            $appToDelete = htmlspecialchars($_GET["delete"]);
            if ($appToDelete != null) {
                $del_query1 = "delete from appointment where aid=$appToDelete";
                if (!mysql_query($del_query1)) {
                    echo "<p>There is a problem with the query: " . mysql_error () . "<p>";
                    exit ();
                }
            }
            // get appointments for the current customer
            $cid = $_SESSION['cid'];
            $query = "select a.aid, a.date, a.time, a.vehicle, a.worker, w.name from appointment a, worker w, vehicle v where a.worker = w.eid and a.vehicle = v.vin and v.owner = $cid order by a.date DESC, a.time ASC;";
            $result = mysql_query($query);
            if (!$result) {
                echo $query;
                echo "<p>There is a problem with the query: " . mysql_error () . "<p>";
                exit ();
            }
            $num = mysql_numrows($result);
            echo "<table class='table'><tr><th>Vehicle</th><th>Date</th><th>Time</th><th>Worker</th><th>Actions</th>";

            for ($i = 0; $i < $num; $i++) {
                echo "<tr>";
                $app_aid = mysql_result($result, $i, 'aid');
                $app_vin = mysql_result($result, $i, 'vehicle');
                $app_date = mysql_result($result, $i, 'date');
                $app_time = mysql_result($result, $i, 'time');
                $app_worker = mysql_result($result, $i, 'name');
                    echo "<td>$app_vin</td> <td>$app_date</td> <td>$app_time</td> <td>$app_worker</td>
    <td><a href='update_app.php?aid=$app_aid' >edit</a>
    <a href='#' onclick=\"validate_delete('$app_aid', '$app_date', '$app_time')\">delete</a></td>";
                echo "</tr>";
            }

            echo "</table>";
            ?>
        </div>

    </div>

</div>

</body>
</html>