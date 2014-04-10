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

<div id="wrapper">

    <?php
    include('../login/session_check.php');
    include('../db_connector.php');
    include('../constants.php');
    $activePage = 'analysis';
    include('../sidebar.php');
    ?>

    <div id="page-wrapper">
        <div class="container">
        <div class="row">  
	<div class="span6">  
	<ul class="nav nav-tabs">  
		<li class="active">  
		<a href="#">Home</a> </li>   
				<li><a href="../analysis/regular_customers.php">Regular Customers</a></li>  
				<li><a href="../analysis/frequent_models.php">Frequent Models</a></li>   
				<li><a href="../analysis/due_vehicles.php">Due Vehicles</a></li>   
				<li><a href="../analysis/parts_usage.php">Parts Usage</a></li>   
				</ul>  
			</div>  
		</div>  
        </div>
    </div>
</div>

</body>
</html>