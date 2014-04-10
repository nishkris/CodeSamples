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
    function validate() {
        var progress = document.getElementById("progress").value;
        if (progress == null || progress == "") 
        {
            bootbox.alert("Enter Progress!");
            return false;
        }
        else if(isNaN(progress)){
            bootbox.alert("Progress cannot be characters");
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
                $ticketProgress = mysql_result($result, 0, 'progress');
                $cancel_url = $server_host . "autoshop/ticket/ticket_details.php?tid=" . $ticketId;

                echo"
                        <h3>Update Progress</h3>
                        <br>
                        <form class='form-horizontal' role='form' action='ticket_details.php?progress=true&tid=$ticketId' onSubmit='return validate()' method='post'>
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
                                <label class='col-md-2 control-label'>Existing Progress</label>
                                <div class='col-md-7'>
                                    <p class='form-control-static'>$ticketProgress%</p>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label for='progress' class='col-md-2 control-label'>New Progress</label>
                                <div class='col-md-2'>
                                    <input type='number' class='form-control' id='progress' name='formProgress' min='$ticketProgress' max='100' value='$ticketProgress'>
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