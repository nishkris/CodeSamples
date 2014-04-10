<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
    <title>Auto Repair Shop</title>
    <link href="../css/sb-admin.css" rel="stylesheet">
    <link rel="stylesheet" href="../font-awesome/css/font-awesome.min.css">

    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script src="../js/bootstrap.js"></script>
    <script src="../js/bootbox.min.js"></script>
    <script src="../js/app.js"></script>
</head>
<body>
<script type="text/javascript">

    $(function() {
        var dateToday = new Date();
        $( "#app_date" ).datepicker({ dateFormat: "yy-mm-dd" , minDate: dateToday});
    });

    function validate() {
        var select_vin = document.getElementById("select_vin").selectedIndex;
        var app_date = document.getElementById("app_date").value;
        var app_time = document.getElementById("app_time").selectedIndex;
        var select_worker = document.getElementById("select_worker").selectedIndex;

        if (select_vin == 0 || app_date == null || app_date == "" || app_time == 0 || select_worker == 0) {
            bootbox.alert("All fields are mandatory!");
            return false;
        }
        return true;
    }

</script>

<div id="wrapper">

    <?php
    include('../login/session_check.php');
    include('../db_connector.php');
    include('../constants.php');
    $activePage = 'app';
    include('../sidebar.php');

    $appToUpdate = htmlspecialchars($_GET["aid"]);
    if ($appToUpdate != null) {
        $query = "select a.date, a.time, a.vehicle, w.name from appointment a, worker w where a.aid=$appToUpdate and a.worker = w.eid";
        $result = mysql_query($query);
        if (!$result) {
            echo "<p>There is a problem with the query: " . mysql_error() . "<p>";
            exit();
        }
        $num = mysql_numrows($result);
        if ($num > 0) {
            $appDate = mysql_result($result, 0, 'date');
            $appTime = mysql_result($result, 0, 'time');
            $appVehicle = mysql_result($result, 0, 'vehicle');
            $appWorker = mysql_result($result, 0, 'name');
    ?>

    <div id="page-wrapper">

        <div class="container">
            <h3>Update Appointment</h3>

            <form role='form' action='db_add_app.php?update=true&aid=<?php echo $appToUpdate; ?>' onSubmit="return validate()" method='post'>
                <?php
                $query = "select vin from vehicle";
                $result = mysql_query($query);
                $num = mysql_numrows($result);
                ?>
                <div class='form-group'>
                    <label>Vehicle
                        <select class="form-control" style="width: 190px" id="select_vin" name="app_vin" onchange="getWorkers()">
                            <option disabled="disabled">-- VIN Number --</option>
                            <?php
                            for ($i = 0; $i < $num; $i++) {
                                $vin = mysql_result($result, $i, 'vin');
                                if ($vin == $appVehicle) {
                                    echo "<option selected='selected'>$vin</option>";
                                } else {
                                    echo "<option>$vin</option>";
                                }
                            }
                            ?>
                        </select>
                    </label>
                </div>
                <div class='form-group'>
                    <label for='app_date'>Date</label>
                    <input style="width: 190px" class='form-control' type="text" id="app_date"
                           name="app_date" onchange="getWorkers()" value="<?php echo $appDate; ?>"/>
                </div>
                <div class='form-group'>
                    <label>Time
                        <select class="form-control" style="width: 110px" id="app_time" name="app_time" onchange="getWorkers()">
                            <option disabled="disabled">-- Time --</option>
                            <?php
                            foreach ($app_times as &$time) {
                                if ($time == substr($appTime, 0, 5)) {
                                    echo "<option selected='selected'>$time</option>";
                                } else {
                                    echo "<option>$time</option>";
                                }
                            }
                            ?>
                        </select>
                    </label>
                </div>
                <div class='form-group'>
                    <label>Worker
                        <select class="form-control" style="width: 250px" id="select_worker"
                                name="app_worker" disabled="disabled">
                            <option disabled="disabled">-- Worker --</option>
                            <option selected="selected"><?php echo $appWorker; ?></option>
                        </select>
                    </label>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
                <button type="button" onclick="history.go(-1);return true;" class="btn btn-primary">
                    Cancel
                </button>
            </form>
        </div>
    </div>
    <?php
        } else {
            echo "<p>Invalid Appointment ID<p>";
            exit();
        }
    }
    ?>
</div>

</body>
</html>
