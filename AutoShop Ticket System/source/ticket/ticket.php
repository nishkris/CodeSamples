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
    <script src="../js/bootbox.min.js"></script>
    <script>
        $(function() {
            $( "#from_date" ).datepicker({ dateFormat: "yy-mm-dd" });
            $( "#to_date" ).datepicker({ dateFormat: "yy-mm-dd" });
        });

        function validate_delete(tid) {
            bootbox.confirm("Delete Ticket : " + tid + "?", function(result) {
                if (result) {
                    window.location = 'ticket.php?delete=' + tid;
                }
            });
        }

        function search(is_worker) {
            var e_sum = document.getElementById("search_summary");
            var e_vin = document.getElementById("search_vin");
            var e_worker = document.getElementById("search_worker");
            var e_status = document.getElementById("search_status");
            var e_from = document.getElementById("from_date");
            var e_to = document.getElementById("to_date");

            var sum = e_sum.value;
            var vin = e_vin.options[e_vin.selectedIndex].text;
            var worker = e_worker.options[e_worker.selectedIndex].text;
            var status = e_status.options[e_status.selectedIndex].text;
            var from = e_from.value;
            var to = e_to.value;

            $.ajax({
                type:"POST",
                url:"search_tickets.php",
                data:{sum:sum, vin:vin, worker:worker, status:status, from:from, to:to}
            }).done(function (result) {
                    var data_array = $.parseJSON(result);
                    tkts_table = document.getElementById("tickets");
                    // remove all except table header
                    $("#tickets tr:gt(0)").remove();
                    // add new rows from the data array
                    for (i = 0; i < data_array.length; i++) {
                        var row = data_array[i];
                        if (is_worker) {
                            $("#tickets").append('<tr><td><a href=\'ticket_details.php?tid=' + row[0] + '\'>' + row[0] + '</a></td><td>' + row[1] + '</td><td>' + row[2] + '</td>' +
                                '<td>' + row[3] + '</td><td>' + row[4] + '</td></tr>');
                        } else {
                            $("#tickets").append('<tr><td><a href=\'ticket_details.php?tid=' + row[0] + '\'>' + row[0] + '</a></td><td>' + row[1] + '</td><td>' + row[2] + '</td>' +
                                '<td>' + row[3] + '</td><td>' + row[4] + '</td><td>' +
                                '<a href=\'update_ticket.php?tid=' + row[0] + '\'>edit</a> <a href=\'#\' onclick=\'validate_delete(' + row[0] + ')\'>delete</a></td></tr>');
                        }
                    }
                });
        }
    </script>
    <style>
        .span2{
            border:solid 1px #0088cc;
            background-color: #e8e8e8;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<!-- Bootstrap core JavaScript -->
<script src="../js/bootstrap.js"></script>

<div id="wrapper">

    <?php
    include('../login/session_check.php');
    include('../db_connector.php');
    include('../constants.php');
    $activePage = 'ticket';
    include('../sidebar.php');

    $tktToDelete = htmlspecialchars($_GET["delete"]);
    if ($tktToDelete != null) {
        $del_query = "delete from ticket where tid=$tktToDelete";
        mysql_query($del_query);
    }

    ?>

    <div id="page-wrapper">

        <div class="container">
            <table style="width: 100%">
                <tr>
                    <td><h3>Tickets</h3></td>
                    <?php if (is_manager()) { ?>
                        <td align="right"><a href="add_ticket.php">Create Ticket</a></td>
                    <?php } ?>
                </tr>
            </table>

            <div class="row">
            <div class="span2">
            <table style="width: 100%">
                <tr style="padding: 5px">
                    <td  style="padding: 5px"><input style="width: 160px" class='form-control' type="text" id="search_summary" placeholder="Keyword in summary"/></td>
                    <?php
                    $query = "select vin from vehicle";
                    $result = mysql_query($query);
                    $num = mysql_numrows($result);
                    ?>
                    <td  style="padding: 5px">
                        <select class="form-control" style="width: 190px" id="search_vin" name="select_vin">
                            <option selected="selected" disabled="disabled">-- VIN Number --</option>
                            <option>Any</option>
                            <?php
                            for ($i = 0; $i < $num; $i++) {
                                $vin = mysql_result($result, $i, 'vin');
                                echo "<option>$vin</option>";
                            }
                            ?>
                        </select>
                    </td>
                    <?php
                    $query1 = "select name from worker";
                    $result1 = mysql_query($query1);
                    $num1 = mysql_numrows($result1);
                    ?>
                    <td  style="padding: 5px">
                        <select class="form-control" style="width: 170px" id="search_worker" name="select_worker">
                            <option selected="selected" disabled="disabled">-- A Worker --</option>
                            <option>Any</option>
                            <?php
                            for ($i = 0; $i < $num1; $i++) {
                                $name = mysql_result($result1, $i, 'name');
                                echo "<option>$name</option>";
                            }
                            ?>
                        </select>
                    </td>
                    <td  style="padding: 5px">
                        <select class="form-control" style="width: 150px" id="search_status" name="select_status">
                            <option selected="selected" disabled="disabled">-- Status --</option>
                            <option>Any</option>
                            <?php
                            echo "<option>$ticket_status_open</option>";
                            echo "<option>$ticket_status_prog</option>";
                            echo "<option>$ticket_status_wait</option>";
                            echo "<option>$ticket_status_resolved</option>";
                            echo "<option>$ticket_status_closed</option>";
                            ?>
                        </select>
                    </td>
                    <td  style="padding: 5px"><input style="width: 100px" class='form-control' type="text" id="from_date" placeholder="From date"/></td>
                    <td  style="padding: 5px"><input style="width: 100px" class='form-control' type="text" id="to_date" placeholder="To date"/></td>
                    <td  style="padding: 5px"><button type="button" onclick="search(<?php if (is_worker()) { echo 'true'; } else { echo 'false'; } ?>)" class="btn btn-primary">Search</button></td>
                </tr>
            </table>
                </div>
                </div>

            <br/>

            <?php
            $query = "select * from ticket";
            $result = mysql_query($query);

            $num = mysql_numrows($result);

            if (is_manager()) {
                echo "<table class='table' id='tickets'><tr><th>Ticket ID</th><th>Summary</th>
          <th>Date Created</th><th>Status</th><th>Vehicle</th><th>Actions</th>";
            } else if (is_worker()) {
                echo "<table class='table' id='tickets'><tr><th>Ticket ID</th><th>Summary</th>
          <th>Date Created</th><th>Status</th><th>Vehicle</th>";
            }

            for ($i = 0; $i < $num; $i++) {
                echo "<tr>";
                $tktTid = mysql_result($result, $i, 'tid');
                $tktSummary = mysql_result($result, $i, 'summary');
                $tktDate = mysql_result($result, $i, 'date');
                $tktStatus = mysql_result($result, $i, 'status');
                $tktVehicle = mysql_result($result, $i, 'vehicle');
                if (is_manager()) {
                    echo "<td><a href='ticket_details.php?tid=$tktTid'>$tktTid</a></td> <td>$tktSummary</td><td>$tktDate</td><td>$tktStatus</td><td>$tktVehicle</td>
              <td><a href='update_ticket.php?tid=$tktTid'>edit</a> <a href='#' onclick=\"validate_delete('$tktTid')\">delete</a></td></tr>";
                } else if (is_worker()) {
                    echo "<td><a href='ticket_details.php?tid=$tktTid'>$tktTid</a></td> <td>$tktSummary</td><td>$tktDate</td><td>$tktStatus</td><td>$tktVehicle</td></tr>";
                }
            }

            echo "</table>";
            ?>
        </div>

    </div>
</div>

</body>
</html>
