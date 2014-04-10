<?php
session_start();
?>
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

    function validate() {
        var hours = document.getElementById("log_hours").value;
        if(hours == null || hours == ""){
            bootbox.alert("Hours cannot be empty");
            return false;
        }
        if(isNaN(hours)){
            bootbox.alert("Hours cannot be characters");
            return false;
        }
        return true;
    }

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
            $query = "select * from ticket where tid=$ticketId";
            $result = mysql_query($query);

            $num = mysql_numrows($result);
            if ($num > 0) {
                $ticketSummary = mysql_result($result, 0, 'summary');
                $ticketWork_hours = mysql_result($result, 0, 'work_hours');
                $cancel_url = $server_host . "autoshop/ticket/ticket_details.php?tid=" . $ticketId;

            echo"
                        <h3>Log Hours</h3>
                        <br>
                        <form class='form-horizontal' role='form' action='ticket_details.php?wlog=true&tid=$ticketId' method='post'>
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
                                <label class='col-md-2 control-label'>Current Work Hours</label>
                                <div class='col-md-7'>
                                    <p class='form-control-static'>$ticketWork_hours</p>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label for='log_hours' class='col-md-2 control-label'>Log Hours</label>
                                <div class='col-md-1'>
                                    <input type='number' class='form-control' id='log_hours' name='formLog_hours' min='0' value='0'>
                                </div>
                                <div class='col-md-4'>
                                    <button type='submit' onclick='return validate()' class='btn btn-primary'>Confirm</button>
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