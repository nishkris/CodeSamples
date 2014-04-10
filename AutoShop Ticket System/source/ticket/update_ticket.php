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
            bootbox.alert("VIN / Appointments cannot be blank.");
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
<div id="wrapper">

    <?php
    include('../login/session_check.php');
    include('../db_connector.php');
    include('../constants.php');
    $activePage = 'ticket';
    include('../sidebar.php');


    $tktToUpdate = htmlspecialchars($_GET["tid"]);

    if ($tktToUpdate != null) {
        $query = "select * from ticket where tid=$tktToUpdate";
        $result = mysql_query($query);

        $num = mysql_numrows($result);
        if ($num > 0) {
            $ticketId = mysql_result($result, 0, 'tid');
            $ticketSummary = mysql_result($result, 0, 'summary');
            $ticketDescription = mysql_result($result, 0, 'description');
            $ticketDate = mysql_result($result, 0, 'date');
            $ticketEstimate = mysql_result($result, 0, 'estimate');
            $ticketStatus = mysql_result($result, 0, 'status');
            $ticketProgress = mysql_result($result, 0, 'progress');
            $ticketWork_hours = mysql_result($result, 0, 'work_hours');
            $ticketVehicle = mysql_result($result, 0, 'vehicle');
            $ticketManager = mysql_result($result, 0, 'manager');
            $ticketAppointment = mysql_result($result, 0, 'appointment');
        }

    } else {
        // if the employee id is null, return to ticket list
        header("Location: " . $server_host . "autoshop/ticket/ticket.php");
        exit();
    }

    ?>

    <div id="page-wrapper">

        <div class="container">
            <h3>Update Ticket</h3>
            <?php
            echo "<form role='form' action='db_add_ticket.php?update=true&tid=$ticketId'  method='post'>";
            ?>
                <div class='form-group'>
                    <label for='summary'>Summary</label>
                    <?php
                    echo "<input type='text' class='form-control' id='summary' name='formTicketSummary' value='$ticketSummary'>";
                    ?>
                </div>
                <?php
                $query = "select vin from vehicle";
                $result = mysql_query($query);
                $num = mysql_numrows($result);
                ?>
                <div class='form-group'>
                    <?php
                    echo "<label>Vehicle
                        <select class='form-control' style='width: 190px' id='select_vin'
                                name='select_vin' onchange=\"getAppointments()\">
                            <option disabled='disabled'>-- VIN Number --</option>";
                    echo "<option selected='selected'>$ticketVehicle</option>";
                    for ($i = 0; $i < $num; $i++) {
                        $vin = mysql_result($result, $i, 'vin');
                        if ($vin != $ticketVehicle) {
                            echo "<option>$vin</option>";
                        }
                    }
                    echo "</select></label>";
                    ?>
                </div>
                <div class='form-group'>
                    <label>Appointment
                        <select class="form-control" style="width: 190px" id="select_app"
                                name="select_app">
                            <option disabled="disabled">-- Appointment --</option>
                            <?php
                            $a_query = "select aid, date, time from appointment where vehicle='$ticketVehicle'";
                            $a_result = mysql_query($a_query);
                            $a_num = mysql_numrows($a_result);
                            for ($i = 0; $i < $a_num; $i++) {
                                $aid = mysql_result($a_result, $i, 'aid');
                                $ticketDate = mysql_result($a_result, $i, 'date');
                                $ticketTime = mysql_result($a_result, $i, 'time');
                                $ticketAppText = $ticketDate." / ".$ticketTime;
                                if ($aid == $ticketAppointment) {
                                    echo "<option selected='selected'>$ticketAppText</option>";
                                } else {
                                    echo "<option>$ticketAppText</option>";
                                }
                            }
                            ?>
                        </select>
                    </label>
                </div>
                <?php
                $query1 = "select eid, name from worker";
                $result1 = mysql_query($query1);
                $num1 = mysql_numrows($result1);

                $query2 = "select worker from ticket_worker where ticket=$ticketId";
                $result2 = mysql_query($query2);
                $num2 = mysql_numrows($result2);
                $current_workers = array();
                for ($i = 0; $i < $num2; $i++) {
                    $current_workers[$i] = mysql_result($result2, $i, 'worker');
                }
                ?>
                <div class='form-group'>
                    <label>Workers
                        <select multiple="multiple" class="form-control" style="width: 250px"
                                id="select_workers"
                                name="select_workers[]">
                            <?php
                            for ($i = 0; $i < $num1; $i++) {
                                $name = mysql_result($result1, $i, 'name');
                                $eid = mysql_result($result1, $i, 'eid');
                                if (in_array($eid, $current_workers)) {
                                    echo "<option selected='selected'>$name</option>";
                                } else {
                                    echo "<option>$name</option>";
                                }
                            }
                            ?>
                        </select>
                    </label>
                </div>
                <div class='form-group'>
                    <label for='estimate'>Estimate</label>
                    <?php
                    echo "<input style='width: 250px' type='text' class='form-control' id='estimate'
                           name='formTicketEstimate' value='$ticketEstimate'>";                    
                    ?>
                </div>
                <div class='form-group'>
                    <label for='description'>Description</label>
                    <?php
                    echo "<textarea rows='10' class='form-control' id='description' name='formTicketDescription'>$ticketDescription</textarea>";
                    ?>
                </div>
                <button type="submit" class="btn btn-primary" onclick='return validate()'>Update</button>
                <button type="button" onclick="history.go(-1);return true;" class="btn btn-primary">
                    Cancel
                </button>

            </form>
        </div>
    </div>
</div>
</body>
</html>
