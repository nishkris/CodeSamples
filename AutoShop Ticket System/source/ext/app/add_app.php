<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <link href="../ui/bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
    <link href="../ui/bootstrap/css/sb-admin.css" rel="stylesheet">
    <!--<link href="../../bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">-->
    <title>Auto Repair Shop</title>
    <link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css">

    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script src="../../js/bootstrap.js"></script>
    <script src="../../js/bootbox.min.js"></script>
    <script src="../../js/app.js"></script>
</head>
<body>
<script type="text/javascript">

    $(function() {
        var dateToday = new Date();
        $( "#app_date" ).datepicker({ dateFormat: "yy-mm-dd" , minDate: dateToday});
    });
function validate() {
        var select_vin = document.getElementById("select_vin").value;
        var app_date = document.getElementById("app_date").value;
        var app_time = document.getElementById("app_time").value;
        var select_worker = document.getElementById("select_worker").value;

        if (select_vin == null || select_vin == "" || app_date == null || app_date == "" || app_time == null || app_time == ""
        || select_worker == null || select_worker == "" || app_date.isEmpty() ) {
            bootbox.alert("All fields are mandatory!");
            return false;
        }
        return true;
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
            <h3>Add Appointment</h3>

            <form role='form' action='db_add_app.php' method='post' onSubmit="return validate()">
                <?php
                // get vehicles for the current customer
                $cid = $_SESSION['cid'];
                $query = "select vin from vehicle where owner = $cid";
                $result = mysql_query($query);
                $num = mysql_numrows($result);
                ?>
                <div class='form-group'>
                    <label>Vehicle
                        <select class="form-control" style="width: 190px" id="select_vin" name="app_vin" onchange="getWorkers()">
                            <option selected="selected" disabled="disabled">-- VIN Number --</option>
                            <?php
                            for ($i = 0; $i < $num; $i++) {
                                $vin = mysql_result($result, $i, 'vin');
                                echo "<option>$vin</option>";
                            }
                            ?>
                        </select>
                    </label>
                </div>
                <div class='form-group'>
                    <label for='app_date'>Date</label>
                    <input style="width: 190px" class='form-control' type="text" id="app_date" name="app_date" onchange="getWorkers()" placeholder="Date"/>
                </div>
                <div class='form-group'>
                    <label>Time
                        <select class="form-control" style="width: 110px" id="app_time" name="app_time" onchange="getWorkers()">
                            <option selected="selected" disabled="disabled">-- Time --</option>
                            <?php
                            foreach ($app_times as &$time) {
                                echo "<option>$time</option>";
                            }
                            ?>
                        </select>
                    </label>
                </div>
                <div class='form-group'>
                    <label>Worker
                        <select class="form-control" style="width: 250px" id="select_worker"
                                name="app_worker" disabled="disabled">
                            <option selected="selected" disabled="disabled">-- Worker --</option>
                        </select>
                    </label>
                </div>
                <button type="submit" class="btn btn-primary">Add</button>
                <button type="button" onclick="history.go(-1);return true;" class="btn btn-primary">
                    Cancel
                </button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
