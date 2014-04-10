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

    function validate_delete(name, mId) {
        bootbox.confirm("Delete manager : " + name + "?", function(result) {
            if (result) {
                window.location = 'manager.php?delete=' + mId;
            }
        });
    }

</script>

<div id="wrapper">

    <?php
    include('../login/session_check.php');
    include('../db_connector.php');
    include('../constants.php');
    $activePage = 'manager';
    include('../sidebar.php');
    ?>

    <div id="page-wrapper">

        <div class="container">
            <table style="width: 100%">
                <tr>
                    <td><h3>Available Managers</h3></td>
                    <td align="right"><a href="add_manager.php">Add New Manager</a></td>
                </tr>
            </table>

            <?php
            $empToDelete = htmlspecialchars($_GET["delete"]);
            if ($empToDelete != null) {
                $del_query1 = "delete from manager where eid=$empToDelete";
                mysql_query($del_query1);
                $del_query2 = "delete from internal_user where eid=$empToDelete";
                mysql_query($del_query2);
            }

            $query = "select * from manager order by name";
            $result = mysql_query($query);

            $num = mysql_numrows($result);

            echo "<table class='table'><tr><th>Employee ID</th><th>Name</th>
<th>Phone</th><th>Address</th><th>Email</th><th>Actions</th>";

            for ($i = 0; $i < $num; $i++) {
                echo "<tr>";
                $managerEmpId = mysql_result($result, $i, 'eid');
                $managerName = mysql_result($result, $i, 'name');
                $managerPhone = mysql_result($result, $i, 'phone');
                $managerAddress = mysql_result($result, $i, 'address');
                $managerEmail = mysql_result($result, $i, 'email');
                echo "<td>$managerEmpId</td> <td>$managerName</td> <td>$managerPhone</td> <td>$managerAddress</td>
    <td>$managerEmail</td> <th><a href='update_manager.php?eid=$managerEmpId' >edit</a>
    <a href='#' onclick=\"validate_delete('$managerName', '$managerEmpId')\" >delete</a></th>";
                echo "</tr>";
            }

            echo "</table>";
            ?>
        </div>

    </div>
</div>

</body>
</html>