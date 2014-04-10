<!DOCTYPE html>
<html lang="en">
<head>
    <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
    <title>Auto Repair Shop</title>
    <link href="../css/sb-admin.css" rel="stylesheet">
    <link rel="stylesheet" href="../font-awesome/css/font-awesome.min.css">

</head>
<body>
<script type="text/javascript">
    function getAppointments() {
        var e = document.getElementById("select_vin");
        var vin = e.options[e.selectedIndex].text;
        $.ajax({
            type:"POST",
            url:"get_appointments.php",
            data:{vin:vin}
        }).done(function (result) {
                var data_array = $.parseJSON(result);
                e = document.getElementById("select_app");
                var count = $('#select_app option').size();
                // remove all except 1st element -- Model --
                for (i = 1; i < count; i++) {
                    e.remove(1);
                }

                for (i = 0; i < data_array.length; i++) {
                    var option = document.createElement("option");
                    option.text = data_array[i];
                    e.add(option, null);
                }
                e.options[0].selected = true;
                e.disabled = false;
            });
    }

    function validate() {
        var vin = document.getElementById("select_vin").value;
        var app = document.getElementById("select_app").value;
        var estimate = document.getElementById("estimate").value;
        var description = document.getElementById("description").value;
        var summary = document.getElementById("summary").value;
        var e_workers = document.getElementById("select_workers");

        if(vin=="-- VIN Number --"||vin==null||app=="-- Appointment --"||app==null)
        {
            bootbox.alert("VIN / Appointments cannot be blank!");
            return false;
        }
        if (!isFinite(estimate) || isNaN(estimate))
        {
            bootbox.alert("Estimate cannot be characters");
            return false;
        } else if (summary == null || summary == "" || description == null || description == "") {
            bootbox.alert("Summary and Description are mandatory!");
            return false;
        } else if (e_workers.selectedIndex == -1) {
            bootbox.alert("At least one worker must be selected!");
            return false;
        }
        if (!isValid(summary) || !isValid(estimate) || !isValid(description)) {
            bootbox.alert("No special characters (~`!#$%\^&*+=\-\[\]\';\/{}|\":<>\?) are allowed on any of the fields !");
            return false;
        }
        return true;
    }

    // check strings for special characters
    function isValid(str){
        return !/[~`!#$%\^&*+=\[\]\\';\/{}|\\":<>\?]/g.test(str);
    }
</script>

<!-- Bootstrap core JavaScript -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="../js/bootstrap.js"></script>
<script src="../js/bootbox.min.js"></script>
<div id="wrapper">

    <?php
    include('../login/session_check.php');
    include('../db_connector.php');
    include('../constants.php');
    $activePage = 'ticket';
    include('../sidebar.php');
    ?>

    <div id="page-wrapper">

        <div class="container">
            <h3>Create Ticket</h3>

            <form role='form' action='db_add_ticket.php'  onsubmit='return validate()' method='post'>
                <div class='form-group'>
                    <label for='summary'>Summary</label>
                    <input type='text' class='form-control' id='summary' name="formTicketSummary"
                           placeholder='Summary of the repair'>
                </div>
                <?php
                $query = "select vin from vehicle";
                $result = mysql_query($query);
                $num = mysql_numrows($result);
                ?>
                <div class='form-group'>
                    <label>Vehicle
                        <select class="form-control" style="width: 190px" id="select_vin" name="select_vin" onchange="getAppointments()">
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
                    <label>Appointment
                        <select class="form-control" style="width: 190px" id="select_app" name="select_app" disabled="disabled">
                            <option selected="selected" disabled="disabled">-- Appointment --</option>
                        </select>
                    </label>
                </div>
                <?php
                $query1 = "select name from worker";
                $result1 = mysql_query($query1);
                $num1 = mysql_numrows($result1);
                ?>
                <div class='form-group'>
                    <label>Workers
                        <select multiple="multiple" size="6" class="form-control" style="width: 250px" id="select_workers"
                                name="select_workers[]">
                            <?php
                            for ($i = 0; $i < $num1; $i++) {
                                $name = mysql_result($result1, $i, 'name');
                                echo "<option>$name</option>";
                            }
                            ?>
                        </select>
                    </label>
                </div>
                <div class='form-group'>
                    <label for='estimate'>Estimate</label>
                    <input style="width: 250px" type='text' class='form-control' id='estimate' name="formTicketEstimate"
                           placeholder='Initial cost estimate'>
                </div>
                <div class='form-group'>
                    <label for='description'>Description</label>
                    <textarea rows="10" class='form-control' id='description' name="formTicketDescription"
                              placeholder='A detailed description to help worker'></textarea>
                </div>
                <button type="submit"  class="btn btn-primary">Add</button>
                <button type="button" onclick="history.go(-1);return true;" class="btn btn-primary">
                    Cancel
                </button>

            </form>
        </div>
    </div>
</div>
</body>
</html>
