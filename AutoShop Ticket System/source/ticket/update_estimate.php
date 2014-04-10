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
    function cancel(url) {
        window.location = url;
    }
</script>
<script type="text/javascript">
    function validate() {
        var estimate = document.getElementById("estimate").value;
        if(estimate == null || estimate == ""){
            bootbox.alert("Estimate cannot be empty");
            return false;
        }
        if(isNaN(estimate)){
            bootbox.alert("Estimate cannot be characters");
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
                $ticketEstimate = mysql_result($result, 0, 'estimate');
                $cancel_url = $server_host . "autoshop/ticket/ticket_details.php?tid=" . $ticketId;

                echo"
                        <h3>Update Estimate</h3>
                        <br>
                        <form class='form-horizontal' role='form' action='ticket_details.php?estimate=true&tid=$ticketId' onsubmit='return validate()' method='post'>
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
                                <label class='col-md-2 control-label'>Current Estimate</label>
                                <div class='col-md-7'>
                                    <p class='form-control-static'>$$ticketEstimate</p>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label for='estimate' class='col-md-2 control-label'>New Estimate</label>
                                <div class='col-md-1'>
                                    <input type='text' class='form-control' id='estimate' name='formEstimate'>
                                </div>
                                <div class='col-md-4'>
                                    <button type='submit' class='btn btn-primary'>Confirm</button>
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