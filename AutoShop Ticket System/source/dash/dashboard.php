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
    $activePage = 'dash';
    include('../sidebar.php');
    ?>

    <div id="page-wrapper">

        <div class="container">

            <br>

            <div class="col-lg-5">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <?php
                        if (is_manager()) {
                            echo "<h3 class='panel-title'><i class='fa fa-money'></i> Today's Appointments</h3>";
                        } else if (is_worker()) {
                            echo "<h3 class='panel-title'><i class='fa fa-money'></i> My Today's Appointments</h3>";
                        }
                        ?>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped tablesorter">
                                <thead>
                                <tr>
                                    <th>App # <i class="fa fa-sort"></i></th>
                                    <th>App Time <i class="fa fa-sort"></i></th>
                                    <th>VIN <i class="fa fa-sort"></i></th>
                                    <?php if (is_manager()) { ?>
                                    <th>Worker <i class="fa fa-sort"></i></th>
                                    <?php } ?>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if (is_worker()) {
                                    $eid = $_SESSION['eid'];
                                    $query = "select aid, time, vehicle, worker from appointment where date = curdate() and worker='$eid' order by time;";
                                } else if (is_manager()) {
                                    $query = "select aid, time, vehicle, worker from appointment where date = curdate() order by time;";
                                }
                                $result = mysql_query($query);
                                if (!$result) {
                                    echo "<p>There is a problem with the query: " . mysql_error () . "<p>";
                                    exit ();
                                }
                                $num = mysql_numrows($result);
                                for ($i = 0; $i < $num; $i++) {
                                    echo "<tr>";
                                    $app_aid = mysql_result($result, $i, 'aid');
                                    $app_time = mysql_result($result, $i, 'time');
                                    $app_vin = mysql_result($result, $i, 'vehicle');
                                    $app_worker = mysql_result($result, $i, 'worker');
                                    if (is_worker()) {
                                        echo "<td>$app_aid</td> <td>$app_time</td> <td>$app_vin</td>";
                                    } else if (is_manager()) {
                                        echo "<td>$app_aid</td> <td>$app_time</td> <td>$app_vin</td> <td>$app_worker</td>";
                                    }
                                    echo "</tr>";
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-right">
                            <a href="../app/appointment.php">View All Appointments <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (is_worker()) { ?>
            <div class="col-lg-5">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-money"></i> My Open/In Prog Tickets</h3>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped tablesorter">
                                <thead>
                                <tr>
                                    <th>ID <i class="fa fa-sort"></i></th>
                                    <th>Status <i class="fa fa-sort"></i></th>
                                    <th>Vehicle <i class="fa fa-sort"></i></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $query = "select t.tid, t.status, t.vehicle from ticket t, ticket_worker tw where tw.ticket=t.tid and (t.status='Open' or t.status='In Progress') and tw.worker='$eid';";
                                $result = mysql_query($query);
                                if (!$result) {
                                    echo "<p>There is a problem with the query: " . mysql_error () . "<p>";
                                    exit ();
                                }
                                $num = mysql_numrows($result);
                                for ($i = 0; $i < $num; $i++) {
                                    echo "<tr>";
                                    $tid = mysql_result($result, $i, 'tid');
                                    $status = mysql_result($result, $i, 'status');
                                    $vehicle = mysql_result($result, $i, 'vehicle');
                                    echo "<td>$tid</td> <td>$status</td> <td>$vehicle</td>";
                                    echo "</tr>";
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-right">
                            <a href="../ticket/ticket.php">View All Tickets <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <?php }
            if (is_manager()) { ?>
            <div class="col-lg-5">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-money"></i> Long Running Tickets</h3>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped tablesorter">
                                <thead>
                                <tr>
                                    <th>ID <i class="fa fa-sort"></i></th>
                                    <th>Date Created <i class="fa fa-sort"></i></th>
                                    <th>Status <i class="fa fa-sort"></i></th>
                                    <th>Days <i class="fa fa-sort"></i></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $query = "select tid, date, status, (curdate() - date) as duration from ticket where status<>'Resolved' and status<>'Closed' order by duration DESC limit 6;";
                                $result = mysql_query($query);
                                if (!$result) {
                                    echo "<p>There is a problem with the query: " . mysql_error () . "<p>";
                                    exit ();
                                }
                                $num = mysql_numrows($result);
                                for ($i = 0; $i < $num; $i++) {
                                    echo "<tr>";
                                    $tid = mysql_result($result, $i, 'tid');
                                    $date = mysql_result($result, $i, 'date');
                                    $status = mysql_result($result, $i, 'status');
                                    $duration = mysql_result($result, $i, 'duration');
                                    echo "<td>$tid</td> <td>$date</td><td>$status</td><td>$duration</td>";
                                    echo "</tr>";
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-right">
                            <a href="../ticket/ticket.php">View All Tickets <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-money"></i> Busiest Workers</h3>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped tablesorter">
                                <thead>
                                <tr>
                                    <th>EID <i class="fa fa-sort"></i></th>
                                    <th>Name <i class="fa fa-sort"></i></th>
                                    <th>Open/In Prog Tickets <i class="fa fa-sort"></i></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $query = "select tw.worker, w.name, count(*) as count from ticket t, ticket_worker tw, worker w where tw.worker=w.eid and (t.status='Open' or t.status='In Progress') and t.tid=tw.ticket group by tw.worker order by count DESC limit 6;";
                                $result = mysql_query($query);
                                if (!$result) {
                                    echo "<p>There is a problem with the query: " . mysql_error () . "<p>";
                                    exit ();
                                }
                                $num = mysql_numrows($result);
                                for ($i = 0; $i < $num; $i++) {
                                    echo "<tr>";
                                    $worker = mysql_result($result, $i, 'worker');
                                    $name = mysql_result($result, $i, 'name');
                                    $count = mysql_result($result, $i, 'count');
                                    echo "<td>$worker</td> <td>$name</td> <td>$count</td>";
                                    echo "</tr>";
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-right">
                            <a href="../worker/worker.php">View All Workers <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-money"></i> Tickets Summary</h3>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped tablesorter">
                                <thead>
                                <tr>
                                    <th>Status <i class="fa fa-sort"></i></th>
                                    <th>Count <i class="fa fa-sort"></i></th>
                                    <th>Percentage <i class="fa fa-sort"></i></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $query = "select status, count(*) as count, (count(*)/(select count(*) from ticket)) * 100 as percentage from ticket group by status order by count DESC;";
                                $result = mysql_query($query);
                                if (!$result) {
                                    echo "<p>There is a problem with the query: " . mysql_error () . "<p>";
                                    exit ();
                                }
                                $num = mysql_numrows($result);
                                for ($i = 0; $i < $num; $i++) {
                                    echo "<tr>";
                                    $status = mysql_result($result, $i, 'status');
                                    $count = mysql_result($result, $i, 'count');
                                    $percentage = mysql_result($result, $i, 'percentage');
                                    $percentage = round($percentage, 2);
                                    echo "<td>$status</td> <td>$count</td> <td>$percentage</td>";
                                    echo "</tr>";
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-right">
                            <a href="../ticket/ticket.php">View All Tickets <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>

</body>
</html>