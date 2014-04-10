<?php
include "constants.php";

function isActive($name, $activePage) {
    if ($activePage != null && $activePage == $name) {
        return "class=\"active\"";
    }
    return "";
}

$logout_page = $server_host . "autoshop/login/logout.php";
if (is_manager()) {
    $query = "select count(*) as count from ticket t where t.status='Waiting on Customer';";
    $result = mysql_query($query);
    if (!$result) {
        echo "<p>There is a problem with the query: " . mysql_error() . "<p>";
        exit();
    }
    $to_approve = mysql_result($result, 0, 'count');
}

?>

<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse"
                data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <?php
        $landing = $server_host . "autoshop/dash/dashboard.php";
        echo "<a class='navbar-brand' href='$landing'>Auto Repair Shop</a>";
        ?>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav side-nav">
            <li <?php echo isActive('dash', $activePage);?> ><a href="../dash/dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li <?php echo isActive('ticket', $activePage);?> ><a href="../ticket/ticket.php"><i class="fa fa-edit"></i> Tickets</a></li>
            <li <?php echo isActive('app', $activePage);?> ><a href="../app/appointment.php"><i class="fa fa-font"></i> Appointments</a></li>
            <?php if (is_worker()) { ?>
            <li <?php echo isActive('worker', $activePage);?> ><a href="../worker/expertise.php?eid=<?php echo $_SESSION['eid'];?>"><i class="fa fa-user"></i> Expertise</a></li>
            <?php } ?>
            <?php if (is_manager()) { ?>
            <li <?php echo isActive('customer', $activePage);?> ><a href="../customer/customer.php"><i class="fa fa-user"></i> Customers</a></li>
            <li <?php echo isActive('worker', $activePage);?> ><a href="../worker/worker.php"><i class="fa fa-wrench"></i> Workers</a></li>
            <li <?php echo isActive('manager', $activePage);?> ><a href="../manager/manager.php"><i class="fa fa-table"></i> Managers</a></li>
            <li <?php echo isActive('part', $activePage);?> ><a href="../part/showPart.php"><i class="fa fa-bolt"></i> Parts</a></li>
            <li <?php echo isActive('model', $activePage);?> ><a href="../vehicle/vehicle_model.php"><i class="fa fa-tasks"></i> Vehicle Models</a></li>
            <li <?php echo isActive('analysis', $activePage);?> ><a href="../analysis/regular_customers.php"><i class="fa fa-bar-chart-o"></i> Analysis</a></li>
            <?php } ?>
        </ul>

        <ul class="nav navbar-nav navbar-right navbar-user">
            <?php if (is_manager()) { ?>
            <li class="dropdown messages-dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i
                    class="fa fa-bell"></i> Alerts <span class="badge"><?php echo $to_approve; ?></span> <b
                    class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li class="dropdown-header">0 New Alert</li>
                    <?php if ($to_approve > 0) { ?>
                    <li class="message-preview">
                        <a href="../ticket/ticket.php">
                            <span class="message">There are <?php echo $to_approve; ?> tickets to be approved...</span>
                        </a>
                    </li>
                    <li class="divider"></li>
                    <?php } ?>
                </ul>
            </li>
            <?php
            }
            $user = $_SESSION["name"]." (".$_SESSION["role"].")";
            ?>

            <li class="dropdown user-dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i
                    class="fa fa-user"></i> <?php echo $user ?><b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="../profile/profile.php"><i class="fa fa-user"></i> Profile</a></li>
                    <li><a href="<?php echo $logout_page; ?>"><i class="fa fa-power-off"></i> Log Out</a></li>
                </ul>
            </li>
        </ul>
    </div>
    <!-- /.navbar-collapse -->
</nav>