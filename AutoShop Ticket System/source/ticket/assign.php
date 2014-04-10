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
            $query = "select t.summary, w.name from ticket t, ticket_worker tw, worker w where t.tid=tw.ticket and tw.worker=w.eid and tid=$ticketId";
            $result = mysql_query($query);

            $num = mysql_numrows($result);
            if ($num > 0) {
                $ticketSummary = mysql_result($result, 0, 'summary');

                $current_workers = array();
                for ($i = 0; $i < $num; $i++) {
                    $current_workers[$i] = mysql_result($result, $i, 'name');
                }

                $query1 = "select name from worker";
                $result1 = mysql_query($query1);
                $num1 = mysql_numrows($result1);

                echo"
                        <h3>Assign Workers</h3>
                        <br>
                        <form class='form-horizontal' role='form' action='ticket_details.php?assign=true&tid=$ticketId' method='post'>
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
                                <label class='col-md-2 control-label'>Assignees</label>
                                <div class='col-md-4'>
                                <select multiple='multiple' size='6' class='form-control' style='width: 250px' id='select_workers' name='formWorkers[]'>
                                ";
                                for ($i = 0; $i < $num1; $i++) {
                                    $name = mysql_result($result1, $i, 'name');
                                    if (in_array($name, $current_workers)) {
                                        echo "<option selected='selected'>$name</option>";
                                    } else {
                                        echo "<option>$name</option>";
                                    }
                                }
                $cancel_url = $server_host . "autoshop/ticket/ticket_details.php?tid=" . $ticketId;
                echo "
                                </select>
                                </div>
                            </div>
                            <div class='form-group'>
                                <div class='col-md-2'></div>
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