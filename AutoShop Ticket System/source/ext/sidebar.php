<?php
include "../constants.php";

function isActive($name, $activePage) {
    if ($activePage != null && $activePage == $name) {
        return "class=\"active\"";
    }
    return "";
}

$logout_page = $ext_context . "login/logout.php";
$cid = $_SESSION['cid'];
$query = "select count(*) as count from ticket t, vehicle v where t.vehicle=v.vin and v.owner=$cid and t.status='Waiting on Customer';";
$result = mysql_query($query);
if (!$result) {
    echo "<p>There is a problem with the query: " . mysql_error() . "<p>";
    exit();
}
$to_approve = mysql_result($result, 0, 'count');

?>

<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse"
                data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <?php
        echo "<a class='navbar-brand' href='../vehicle/vehicle.php'>Auto Repair Shop</a>";
        ?>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav side-nav">
            <li <?php echo isActive('vehicle', $activePage);?> ><a href="../vehicle/vehicle.php"><i class="fa fa-desktop"></i> Vehicles</a></li>
            <li <?php echo isActive('ticket', $activePage);?> ><a href="../ticket/ticket.php"><i class="fa fa-edit"></i> Tickets</a></li>
            <li <?php echo isActive('app', $activePage);?> ><a href="../app/appointment.php"><i class="fa fa-font"></i> Appointments</a></li>
        </ul>

        <ul class="nav navbar-nav navbar-right navbar-user">
            <li class="dropdown messages-dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i
                    class="fa fa-bell"></i> Alerts <span class="badge"><?php echo $to_approve; ?></span> <b
                    class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li class="dropdown-header">1 New Alert</li>
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
            $user = $_SESSION["name"];
            ?>

            <li class="dropdown user-dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i
                    class="fa fa-user"></i> <?php echo $user ?><b class="caret"></b></a>
                <ul class="dropdown-menu">
<!--                    TODO : customer profile-->
                    <li><a href="../profile/profile.php"><i class="fa fa-user"></i> Profile</a></li>
                    <li><a href="<?php echo $logout_page; ?>"><i class="fa fa-power-off"></i> Log Out</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>