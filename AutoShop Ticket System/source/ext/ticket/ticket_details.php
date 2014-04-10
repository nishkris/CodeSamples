<?php
include('../login/session_check.php');
include ('../../db_connector.php');
include ('../../constants.php');
$activePage = 'ticket';
include ('../sidebar.php');
?>
<html lang="en">
<head>
    <link href="../ui/bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
    <link href="../ui/bootstrap/css/sb-admin.css" rel="stylesheet">
    <link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css">
    <title>Auto Repair Shop</title>
</head>
<body>
<!-- Bootstrap core JavaScript -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="../../js/bootstrap.js"></script>
<script src="../../js/bootbox.min.js"></script>

<script type="text/javascript">

    function t_comment(tid) {
        window.location = 'comment.php?tid=' + tid;
    }

    function t_approve(tid) {
        bootbox.confirm("Change status to 'Approved'?", function(result) {
            if (result) {
                window.location = 'ticket_details.php?tid=' + tid + '&status=Approved';
            }
        });
    }

</script>

<div id="wrapper">

<div class="container">
<h3>Ticket Details</h3>

<?php
$ticketId = null;
$ticketComment = null;
$ticketStatus = null;

if (isset ($_GET ['tid'])) {
    $ticketId = htmlspecialchars($_GET ["tid"]);
}

if (isset ( $_GET ['status'] )) {
    $ticketStatus = htmlspecialchars ( $_GET ["status"] );
}

if (isset ($_GET ['comment'])) {
    $ticketComment = htmlspecialchars($_GET ["comment"]);
}

$_SESSION ['tid'] = $ticketId;

if ($ticketId != null) {
    if ($ticketStatus != null) {
        $status_query = "update ticket set status='$ticketStatus' where tid=$ticketId;";
        if (! mysql_query ( $status_query )) {
            echo "<p>There is a problem with the query: " . mysql_error () . "<p>";
            exit ();
        }
    } else if ($ticketComment == 'true') {
        $com = htmlspecialchars($_POST ["formComment"]);
        $cid = $_SESSION ['cid'];
        $com_query = "insert into customer_comment values (0, '$com', $ticketId, $cid, curdate(), curtime());";
        if (!mysql_query($com_query)) {
            echo "<p>There is a problem with the query: " . mysql_error() . "<p>";
            exit ();
        }
    }

    $query = "select * from ticket where tid=$ticketId";
    $result = mysql_query($query);
    $num = mysql_numrows($result);
    if ($num > 0) {
        // $ticketId = mysql_result($result, 0, 'tid');
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
        ?>

    <hr>
    <table style="width: 15%">
        <tr>
            <td><button type='button' onclick='t_comment(<?php echo $ticketId; ?>)' class='btn btn-default btn-sm'>Comment</button></td>
            <td><button type='button' onclick='t_approve(<?php echo $ticketId; ?>)' class='btn btn-default btn-sm'>Approve</button></td>
        </tr>
    </table>
    <hr>

    <form class='form-horizontal' role='form' action='#'
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
            $a_result = mysql_query($a_query);
            $a_date = mysql_result($a_result, 0, 'date');
            $a_time = mysql_result($a_result, 0, 'time');
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
            $w_result = mysql_query($w_query);
            $w_num = mysql_numrows($w_result);
            for ($i = 0; $i < $w_num; $i++) {
                $w_name = mysql_result($w_result, $i, 'name');
                echo "<p class='form-control-static'>$w_name</p>";
            }
            ?>
        </div>
        <label class='col-md-2 control-label'>Created By</label>

        <div class='col-md-2'>
            <?php
            $m_query = "select name from manager where eid='$ticketManager';";
            $m_result = mysql_query($m_query);
            $m_name = mysql_result($m_result, 0, 'name');
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
        $mc_result = mysql_query($mc_query);
        if (!$mc_result) {
            echo "<p>There is a problem with the query: " . mysql_error() . "<p>";
            exit ();
        }
        $mc_num = mysql_numrows($mc_result);
        // worker comments
        $wc_query = "select wc.description, wc.date, wc.time, w.name from worker_comment wc, worker w where wc.worker=w.eid and wc.ticket=$ticketId;";
        $wc_result = mysql_query($wc_query);
        if (!$wc_result) {
            echo "<p>There is a problem with the query: " . mysql_error() . "<p>";
            exit ();
        }
        $wc_num = mysql_numrows($wc_result);
        // customer comments
        $cc_query = "select cc.description, cc.date, cc.time, c.name from customer_comment cc, customer c where cc.customer=c.cid and cc.ticket=$ticketId;";
        $cc_result = mysql_query($cc_query);
        if (!$cc_result) {
            echo "<p>There is a problem with the query: " . mysql_error() . "<p>";
            exit ();
        }
        $cc_num = mysql_numrows($cc_result);

        for ($i = 0; $i < $mc_num; $i++) {
            $des = mysql_result($mc_result, $i, 'description');
            $name = mysql_result($mc_result, $i, 'name');
            $date = mysql_result($mc_result, $i, 'date');
            $time = mysql_result($mc_result, $i, 'time');
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

        for ($i = 0; $i < $wc_num; $i++) {
            $des = mysql_result($wc_result, $i, 'description');
            $name = mysql_result($wc_result, $i, 'name');
            $date = mysql_result($wc_result, $i, 'date');
            $time = mysql_result($wc_result, $i, 'time');
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

        for ($i = 0; $i < $cc_num; $i++) {
            $des = mysql_result($cc_result, $i, 'description');
            $name = mysql_result($cc_result, $i, 'name');
            $date = mysql_result($cc_result, $i, 'date');
            $time = mysql_result($cc_result, $i, 'time');
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
    header("Location: " . $ext_context . "ticket/ticket.php");
    exit ();
}

?>
</div>
</div>
</body>

</html>