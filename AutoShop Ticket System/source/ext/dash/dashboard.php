<!DOCTYPE html>
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

<div id="wrapper">

    <?php
    include('../login/session_check.php');
    include('../../db_connector.php');
    include('../../constants.php');
    $activePage = 'dash';
    include('../sidebar.php');
    ?>

    <div id="page-wrapper">

        <div class="container">
            <p>My History</p>
            <p>Existing Appointments</p>
        </div>
    </div>
</div>

</body>
</html>