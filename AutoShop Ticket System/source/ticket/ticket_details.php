<?php
include('../login/session_check.php');
include ('../db_connector.php');
include ('../constants.php');
$activePage = 'ticket';
include ('../sidebar.php');
?>
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
    function t_edit(tid) {
        window.location = 'update_ticket.php?tid=' + tid;
    }
    function t_delete(tid) {
        bootbox.confirm("Delete Ticket : " + tid + "?", function(result) {
            if (result) {
                window.location = 'ticket.php?delete=' + tid;
            }
        });
    }
    function t_comment(tid) {
        window.location = 'comment.php?tid=' + tid;
    }
    function t_assign(tid) {
        window.location = 'assign.php?tid=' + tid;
    }
    function t_due(tid) {
        window.location = 'due.php?tid=' + tid;
    }
    function t_log_work(tid) {
        window.location = 'log_hours.php?tid=' + tid;
    }
    function t_estimate(tid) {
        window.location = 'update_estimate.php?tid=' + tid;
    }
    function t_update_progress(tid) {
        window.location = 'update_progress.php?tid=' + tid;
    }
    function t_progress(tid) {
        bootbox.confirm("Change status to 'In Progress'?", function(result) {
            if (result) {
                window.location = 'ticket_details.php?tid=' + tid + '&status=In Progress';
            }
        });
    }
    function t_resolve(tid) {
        bootbox.confirm("Resolve Ticket?", function(result) {
            if (result) {
                window.location = 'ticket_details.php?tid=' + tid + '&status=Resolved';
            }
        });
    }
    function t_wait(tid) {
        bootbox.confirm("Change status to 'Waiting On Customer'?", function(result) {
            if (result) {
                window.location = 'ticket_details.php?tid=' + tid + '&status=Waiting On Customer';
            }
        });
    }
    function t_approve(tid) {
        bootbox.confirm("Change status to 'Approved'?", function(result) {
            if (result) {
                window.location = 'ticket_details.php?tid=' + tid + '&status=Approved';
            }
        });
    }
    function t_close(tid) {
        bootbox.confirm("Close Ticket?", function(result) {
            if (result) {
                window.location = 'ticket_details.php?tid=' + tid + '&status=Closed';
            }
        });
    }
</script>

<div id="wrapper">



<div class="container">
<h3>Ticket Details</h3>

<?php
$ticketId = null;
$ticketStatus = null;
$ticketWlog = null;
$ticketProg = null;
$ticketDue = null;
$ticketEst = null;
$ticketComment = null;
$ticketAssign = null;

if (isset ( $_GET ['tid'] )) {
    $ticketId = htmlspecialchars ( $_GET ["tid"] );
}
if (isset ( $_GET ['status'] )) {
    $ticketStatus = htmlspecialchars ( $_GET ["status"] );
}
if (isset ( $_GET ['wlog'] )) {
    $ticketWlog = htmlspecialchars ( $_GET ["wlog"] );
}
if (isset ( $_GET ['progress'] )) {
    $ticketProg = htmlspecialchars ( $_GET ["progress"] );
}
if (isset ( $_GET ['estimate'] )) {
    $ticketEst = htmlspecialchars ( $_GET ["estimate"] );
}
if (isset ( $_GET ['assign'] )) {
    $ticketAssign = htmlspecialchars ( $_GET ["assign"] );
}
if (isset ( $_GET ['comment'] )) {
    $ticketComment = htmlspecialchars ( $_GET ["comment"] );
}
if (isset ( $_GET ['due'] )) {
    $ticketDue = htmlspecialchars ( $_GET ["due"] );
}

$_SESSION ['tid'] = $ticketId;
								
if ($ticketId != null) {
									
    if ($ticketStatus != null) {
        $status_query = "update ticket set status='$ticketStatus' where tid=$ticketId;";
        if (! mysql_query ( $status_query )) {
            echo "<p>There is a problem with the query: " . mysql_error () . "<p>";
            exit ();
        }
    } else if ($ticketWlog == 'true') {
        $log_hours = htmlspecialchars ( $_POST ["formLog_hours"] );
        $wlog_query = "update ticket set work_hours = work_hours + $log_hours where tid=$ticketId;";
        if (! mysql_query ( $wlog_query )) {
            echo "<p>There is a problem with the query: " . mysql_error () . "<p>";
            exit ();
        }
    } else if ($ticketProg == 'true') {
        $prog = htmlspecialchars ( $_POST ["formProgress"] );
        $prog_query = "update ticket set progress = $prog where tid=$ticketId;";
        if (! mysql_query ( $prog_query )) {
            echo "<p>There is a problem with the query: " . mysql_error () . "<p>";
            exit ();
        }
    } else if ($ticketEst == 'true') {
        $est = htmlspecialchars ( $_POST ["formEstimate"] );
        $est_query = "update ticket set estimate = $est where tid=$ticketId;";
        if (! mysql_query ( $est_query )) {
            echo "<p>There is a problem with the query: " . mysql_error () . "<p>";
            exit ();
        }
    } else if ($ticketAssign == 'true') {
        $ticketWorkers = $_POST ["formWorkers"];
        // remove existing workers
        $del_workers = "delete from ticket_worker where ticket=$ticketId;";
        if (! mysql_query ( $del_workers )) {
            echo "<p>There is a problem with the query: " . mysql_error () . "<p>";
            exit ();
        }
        // add new workers
        foreach ( $ticketWorkers as &$worker ) {
            // get worker eid
            $worker_query = "select eid from worker where name='$worker';";
            $result = mysql_query ( $worker_query );
            if (! $result) {
                echo "<p>There is a problem with the query: " . mysql_error () . "<p>";
                exit ();
            }
            $eid = mysql_result ( $result, 0, 'eid' );
            // insert worker for ticket
            $tkt_worker_query = "insert into ticket_worker values('$ticketId', '$eid');";
            if (! mysql_query ( $tkt_worker_query )) {
                echo "<p>There is a problem with the query: " . mysql_error () . "<p>";
                exit ();
            }
        }
    } else if ($ticketComment == 'true') {
        $com = htmlspecialchars ( $_POST ["formComment"] );
        $PartCom = htmlspecialchars ( $_POST ["select_part"] );
        $PartQty = htmlspecialchars ( $_POST ["select_qty"] );
        $user_id = $_SESSION ['eid'];
        $sql = "SELECT role FROM internal_user WHERE eid = '$user_id'";
        $result = mysql_query($sql);
        $user_type = mysql_result($result, 0, 'role');
										
        $partQuery = "SELECT * FROM part WHERE pid = '$PartCom'";
        $partResult = mysql_query($partQuery);
        $partNum = mysql_numrows ($partResult);
										
        if($partNum == 1){
            $insQuery = "insert into ticket_part values ('$ticketId','$PartCom','$PartQty')";
            mysql_query($insQuery);
            $updQuery = "update part set in_stock = in_stock - $PartQty where pid = '$PartCom'";
            $updResult = mysql_query($updQuery);
            $com = $com . " Part : " . mysql_result($partResult, 0, 'brand') . " - " . mysql_result($partResult, 0, 'description') . " Quantity : " . $PartQty;
        }
										
        if ($user_type == 'manager') {
            $com_query = "insert into manager_comment values (0, '$com', $ticketId, '$user_id', curdate(), curtime());";
        } else if ($user_type == 'worker') {
            $com_query = "insert into worker_comment values (0, '$com', $ticketId, '$user_id', curdate(), curtime());";
        } else {
			echo "<p>Invalid Role ID<p>";
        }
        if (! mysql_query ( $com_query )) {
            echo "<p>There is a problem with the query: " . mysql_error () . "<p>";
            exit ();
        }
    }
									
    $query = "select * from ticket where tid=$ticketId";
    $result = mysql_query ( $query );
									
    $num = mysql_numrows ( $result );
    if ($num > 0) {
        // $ticketId = mysql_result($result, 0, 'tid');
        $ticketSummary = mysql_result ( $result, 0, 'summary' );
        $ticketDescription = mysql_result ( $result, 0, 'description' );
        $ticketDate = mysql_result ( $result, 0, 'date' );
        $ticketEstimate = mysql_result ( $result, 0, 'estimate' );
        $ticketStatus = mysql_result ( $result, 0, 'status' );
        $ticketProgress = mysql_result ( $result, 0, 'progress' );
        $ticketWork_hours = mysql_result ( $result, 0, 'work_hours' );
        $ticketVehicle = mysql_result ( $result, 0, 'vehicle' );
        $ticketManager = mysql_result ( $result, 0, 'manager' );
        $ticketAppointment = mysql_result ( $result, 0, 'appointment' );

        // Update due date for vehicle
        if ($ticketDue == 'true') {
            $due_work = htmlspecialchars ( $_POST ["formDueWork"] );
            $due_date = $_POST ["formDueDate"];
            $due_query = "update vehicle set next_due_date = '$due_date', next_due_work='$due_work' where vin='$ticketVehicle';";
            if (! mysql_query ( $due_query )) {
                echo "<p>There is a problem with the query: " . mysql_error () . "<p>";
                exit ();
            }
        }
        ?>

    <hr>
    <table style="width: 80%">
        <tr>
            <?php if (is_manager()) { ?>
            <td><button type='button' onclick='t_edit(<?php echo $ticketId; ?>)' class='btn btn-default btn-sm'>Edit</button></td>
            <td><button type='button' onclick='t_delete(<?php echo $ticketId; ?>)' class='btn btn-default btn-sm'>Delete</button></td>
            <?php } ?>
            <td><button type='button' onclick='t_comment(<?php echo $ticketId; ?>)' class='btn btn-default btn-sm'>Comment</button></td>
            <?php if (is_manager()) { ?>
            <td><button type='button' onclick='t_assign(<?php echo $ticketId; ?>)' class='btn btn-default btn-sm'>Assign</button></td>
            <?php } ?>
            <td><button type='button' onclick='t_log_work(<?php echo $ticketId; ?>)' class='btn btn-default btn-sm'>Log Work</button></td>
            <td><button type='button' onclick='t_estimate(<?php echo $ticketId; ?>)' class='btn btn-default btn-sm'>Update Estimate</button></td>
            <td><button type='button' onclick='t_update_progress(<?php echo $ticketId; ?>)' class='btn btn-default btn-sm'>Update Progress</button></td>
            <td><button type='button' onclick='t_progress(<?php echo $ticketId; ?>)' class='btn btn-default btn-sm'>Start Progress</button></td>
            <td><button type='button' onclick='t_wait(<?php echo $ticketId; ?>)' class='btn btn-default btn-sm'>Wait on Customer</button></td>
            <td><button type='button' onclick='t_due(<?php echo $ticketId; ?>)' class='btn btn-default btn-sm'>Next Due</button></td>
            <?php if (is_manager()) { ?>
            <td><button type='button' onclick='t_approve(<?php echo $ticketId; ?>)' class='btn btn-default btn-sm'>Approve</button></td>
            <?php } ?>
            <td><button type='button' onclick='t_resolve(<?php echo $ticketId; ?>)' class='btn btn-default btn-sm'>Resolve</button></td>
            <?php if (is_manager()) { ?>
            <td><button type='button' onclick='t_close(<?php echo $ticketId; ?>)' class='btn btn-default btn-sm'>Close</button></td>
            <?php } ?>
        </tr>
    </table>
    <hr>

    <form class='form-horizontal' role='form' action='db_log_hours.php'
          method='post'>
    <div class='form-group'>
        <label class='col-md-2 control-label'>Summary</label>
        <div class='col-md-7'>
            <?php
            echo "<p class='form-control-static'>$ticketSummary</p>";
            ?>
        </div>
    </div>
    <hr>
    <div class='form-group'>
        <label class='col-md-2 control-label'>Ticket ID</label>
        <div class='col-md-2'>
            <?php
            echo "<p class='form-control-static'>$ticketId</p>";
            ?>
        </div>
        <label class='col-md-2 control-label'>Appointment</label>
        <div class='col-md-2'>
            <?php
            $a_query = "select date, time from appointment where aid=$ticketAppointment;";
            $a_result = mysql_query ( $a_query );
            $a_date = mysql_result ( $a_result, 0, 'date' );
            $a_time = mysql_result ( $a_result, 0, 'time' );
            $a_text = $a_date . " / " . $a_time;
            echo "<p class='form-control-static'>$a_text</p>";
            ?>
        </div>
    </div>

    <div class='form-group'>
        <label class='col-md-2 control-label'>Vehicle VIN#</label>
        <div class='col-md-2'>
            <?php
            echo "<p class='form-control-static'>$ticketVehicle</p>";
            ?>
        </div>
        <label class='col-md-2 control-label'>Estimate</label>
        <div class='col-md-2'>
            <?php
            echo "<p class='form-control-static'>$" . "$ticketEstimate</p>";
            ?>
        </div>
    </div>

    <div class='form-group'>
        <label class='col-md-2 control-label'>Status</label>
        <div class='col-md-2'>
            <?php
            echo "<p class='form-control-static'>$ticketStatus</p>";
            ?>
        </div>
        <label class='col-md-2 control-label'>Start Date</label>
        <div class='col-md-2'>
            <?php
            echo "<p class='form-control-static'>$ticketDate</p>";
            ?>
        </div>
    </div>

    <div class='form-group'>
        <label class='col-md-2 control-label'>Progress</label>
        <div class='col-md-2'>
            <?php
            echo "<p class='form-control-static'>$ticketProgress" . "%</p>";
            ?>
        </div>
        <label class='col-md-2 control-label'>Work Hours</label>
        <div class='col-md-2'>
            <?php
            echo "<p class='form-control-static'>$ticketWork_hours</p>";
            ?>
        </div>
    </div>

    <div class='form-group'>
        <label class='col-md-2 control-label'>Workers</label>
        <div class='col-md-2'>
            <?php
            $w_query = "select w.name from ticket t, ticket_worker tw, worker w where t.tid = tw.ticket and tw.worker = w.eid and t.tid=$ticketId;";
            $w_result = mysql_query ( $w_query );
            $w_num = mysql_numrows ( $w_result );
            for($i = 0; $i < $w_num; $i ++) {
                $w_name = mysql_result ( $w_result, $i, 'name' );
                echo "<p class='form-control-static'>$w_name</p>";
            }
            ?>
        </div>
        <label class='col-md-2 control-label'>Created By</label>
        <div class='col-md-2'>
            <?php
            $m_query = "select name from manager where eid='$ticketManager';";
            $m_result = mysql_query ( $m_query );
            $m_name = mysql_result ( $m_result, 0, 'name' );
            echo "<p class='form-control-static'>$m_name</p>";
            ?>
        </div>
    </div>

    <hr>
    <div class='form-group'>
        <label class='col-md-2 control-label'>Description</label>
        <div class='col-md-7'>
            <?php
            echo "<p class='form-control-static'>$ticketDescription</p>";
            ?>
        </div>
    </div>
    <hr>
        <?php
        // manager comments
        $mc_query = "select mc.description, mc.date, mc.time, m.name from manager_comment mc, manager m where mc.manager=m.eid and mc.ticket=$ticketId;";
        $mc_result = mysql_query ( $mc_query );
        if (! $mc_result) {
            echo "<p>There is a problem with the query: " . mysql_error () . "<p>";
            exit ();
        }
        $mc_num = mysql_numrows ( $mc_result );
        // worker comments
        $wc_query = "select wc.description, wc.date, wc.time, w.name from worker_comment wc, worker w where wc.worker=w.eid and wc.ticket=$ticketId;";
        $wc_result = mysql_query ( $wc_query );
        if (! $wc_result) {
            echo "<p>There is a problem with the query: " . mysql_error () . "<p>";
            exit ();
        }
        $wc_num = mysql_numrows ( $wc_result );
        // customer comments
        $cc_query = "select cc.description, cc.date, cc.time, c.name from customer_comment cc, customer c where cc.customer=c.cid and cc.ticket=$ticketId;";
        $cc_result = mysql_query ( $cc_query );
        if (! $cc_result) {
            echo "<p>There is a problem with the query: " . mysql_error () . "<p>";
            exit ();
        }
        $cc_num = mysql_numrows ( $cc_result );
										
        for($i = 0; $i < $mc_num; $i ++) {
            $des = mysql_result ( $mc_result, $i, 'description' );
            $name = mysql_result ( $mc_result, $i, 'name' );
            $date = mysql_result ( $mc_result, $i, 'date' );
            $time = mysql_result ( $mc_result, $i, 'time' );
            $head = "<p>" . $name . "&nbsp;&nbsp;&nbsp;&nbsp;" . $date . " " . $time;
            $comment = $head . "</p><p>" . $des . "</p>";
            echo "
            <div class='form-group'>
                <label class='col-md-2 control-label'>";
            $com_label = "";
            if ($i == 0) {
                $com_label = "Manager Comments";
            }
            echo $com_label;
            echo "</label>
                <div class='col-md-7' style='background-color: #e8e8e8;padding-top: 6px'>
                    $comment
                </div>
            </div>";
        }
										
        echo "<hr>";
										
        for($i = 0; $i < $wc_num; $i ++) {
            $des = mysql_result ( $wc_result, $i, 'description' );
            $name = mysql_result ( $wc_result, $i, 'name' );
            $date = mysql_result ( $wc_result, $i, 'date' );
            $time = mysql_result ( $wc_result, $i, 'time' );
            $head = "<p>" . $name . "&nbsp;&nbsp;&nbsp;&nbsp;" . $date . " " . $time;
            $comment = $head . "</p><p>" . $des . "</p>";
            echo "
            <div class='form-group'>
                <label class='col-md-2 control-label'>";
            $com_label = "";
            if ($i == 0) {
                $com_label = "Worker Comments";
            }
            echo $com_label;
            echo "</label>
                <div class='col-md-7' style='background-color: #e8e8e8;padding-top: 6px'>
                    $comment
                </div>
            </div>";
        }
										
        echo "<hr>";
										
        for($i = 0; $i < $cc_num; $i ++) {
            $des = mysql_result ( $cc_result, $i, 'description' );
            $name = mysql_result ( $cc_result, $i, 'name' );
            $date = mysql_result ( $cc_result, $i, 'date' );
            $time = mysql_result ( $cc_result, $i, 'time' );
            $head = "<p>" . $name . "&nbsp;&nbsp;&nbsp;&nbsp;" . $date . " " . $time;
            $comment = $head . "</p><p>" . $des . "</p>";
            echo "
            <div class='form-group'>
                <label class='col-md-2 control-label'>";
            $com_label = "";
            if ($i == 0) {
                $com_label = "Customer Comments";
            }
            echo $com_label;
            echo "</label>
                <div class='col-md-7' style='background-color: #e8e8e8;padding-top: 6px'>
                    $comment
                </div>
            </div>";
        }
        ?>
    </form>

        <?php
    }
} else {
    // if the ticket id is null, return to ticket list
    header ( "Location: " . $server_host . "autoshop/ticket/ticket.php" );
    exit ();
}
								
?>
</div>
</div>
</body>

</html>