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

    function validate_delete(name, empId) {
        bootbox.confirm("Delete worker : " + name + "?", function(result) {
            if (result) {
                window.location = 'worker.php?delete=' + empId;
            }
        });
    }

</script>

<div id="wrapper">

    <?php
    include('../login/session_check.php');
    include('../db_connector.php');
    include('../constants.php');
    $activePage = 'worker';
    include('../sidebar.php');
    ?>

    <div id="page-wrapper">
        <div class="container">
            <table style="width: 100%">
                <tr>
                    <td><h3>Available Workers</h3></td>
                    <td align="right"><a href="add_worker.php">Add New Worker</a></td>
                </tr>
            </table>

            <?php

            $empToDelete = htmlspecialchars($_GET["delete"]);
            if ($empToDelete != null) {
                $del_query1 = "delete from worker where eid=$empToDelete";
                mysql_query($del_query1);
                $del_query2 = "delete from internal_user where eid=$empToDelete";
                mysql_query($del_query2);
            }

            $query = "select * from worker order by name;";
            $result = mysql_query($query);

            $num = mysql_numrows($result);

            echo "<table class='table'><tr><th>Employee ID</th><th>Name</th>
<th>Phone</th><th>Address</th><th>Email</th><th>Actions</th>";

            for ($i = 0; $i < $num; $i++) {
                echo "<tr>";
                $workerEmpId = mysql_result($result, $i, 'eid');
                $workerName = mysql_result($result, $i, 'name');
                $workerPhone = mysql_result($result, $i, 'phone');
                $workerAddress = mysql_result($result, $i, 'address');
                $workerEmail = mysql_result($result, $i, 'email');
                echo "<td>$workerEmpId</td> <td>$workerName</td> <td>$workerPhone</td> <td>$workerAddress</td>
    <td>$workerEmail</td> <td><a href='update_worker.php?eid=$workerEmpId' >edit</a>
    <a href='#' onclick=\"validate_delete('$workerName', '$workerEmpId')\">delete</a>
    <a href='expertise.php?eid=$workerEmpId' >expertise</a>
    </td>";
                echo "</tr>";
            }

            echo "</table>";
            ?>
        </div>

    </div>

</div>

</body>
</html>