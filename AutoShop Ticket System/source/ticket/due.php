<?php
session_start();
?>
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
</head>
<body>
<!-- Bootstrap core JavaScript -->
<script src="../js/bootstrap.js"></script>
<script src="../js/bootbox.min.js"></script>
<script type="text/javascript">

    $(function() {
        var dateToday = new Date();
        $( "#due_date" ).datepicker({ dateFormat: "yy-mm-dd", minDate: dateToday});
    });

    function cancel(url) {
        window.location = url;
    }

</script>

<div id="wrapper">

    <?php
    include('../login/session_check.php');
    include('../db_connector.php');
    include('../constants.php');
    $activePage = 'ticket';
    include('../sidebar.php');
    ?>

    <div class="container">

        <?php
        $ticketId = htmlspecialchars($_GET["tid"]);
        session_start();

        $_SESSION['tid']=$ticketId;

        if ($ticketId != null) {
            $query = "select t.summary, v.vin from ticket t, vehicle v where t.vehicle = v.vin and tid=$ticketId";
            $result = mysql_query($query);

            $num = mysql_numrows($result);
            if ($num > 0) {
                $ticketSummary = mysql_result($result, 0, 'summary');
                $vin = mysql_result($result, 0, 'vin');
                $cancel_url = $server_host . "autoshop/ticket/ticket_details.php?tid=" . $ticketId;
                echo"
                        <h3>Add Next Due Work</h3>
                        <br>
                        <form class='form-horizontal' role='form' action='ticket_details.php?due=true&tid=$ticketId' method='post'>
                            <div class='form-group'>
                                <label class='col-md-2 control-label'>Ticket ID</label>
                                <div class='col-md-2'>
                                    <p class='form-control-static'>$ticketId</p>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label class='col-md-2 control-label'>Summary</label>
                                <div class='col-md-7'>
                                    <p class='form-control-static'>$ticketSummary</p>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label class='col-md-2 control-label'>VIN</label>
                                <div class='col-md-7'>
                                    <p class='form-control-static'>$vin</p>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label class='col-md-2 control-label'>Next Due Work</label>
                                <div class='col-md-8'>
                                    <input type='text' class='form-control' id='dueWork' name='formDueWork' placeholder='Summary of next work'>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label class='col-md-2 control-label'>Due Date</label>
                                <div class='col-md-8'>
                                    <input style='width: 100px' class='form-control' type='text' id='due_date' name='formDueDate' placeholder='Select date'/>
                                </div>
                            </div>
                            <div class='form-group'>
                                <div class='col-md-2'></div>
                                <div class='col-md-4'>
                                    <button type='submit' class='btn btn-primary'>Add</button>
                                    <button type='button' onclick=\"cancel('$cancel_url')\" class='btn btn-primary'>Cancel</button>
                                </div>
                            </div>
                        </form>
                    ";
            }
        }
        else {
            // if the ticket id is null, return to ticket list
            header("Location: " . $server_host . "autoshop/ticket/ticket.php");
            exit();
        }

        ?>
    </div>
</div>
</body>

</html>