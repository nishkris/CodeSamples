<!DOCTYPE html>
<html lang="en">
<head>
    <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
    <!-- Add custom CSS here -->
    <link href="../css/sb-admin.css" rel="stylesheet">
    <link rel="stylesheet" href="../font-awesome/css/font-awesome.min.css">

    <title>Auto Repair Shop</title>

<!--    <script src="http://code.jquery.com/jquery-latest.js"></script>-->
    <script src="../js/vehicle.js"></script>
    <script type="text/javascript">
        function search() {
            var e = document.getElementById("select_brand");
            var brand = create_param(e.options[e.selectedIndex].text, 'brand');
            e = document.getElementById("select_model");
            var model = create_param(e.options[e.selectedIndex].text, 'model');
            e = document.getElementById("select_trim");
            var trim = create_param(e.options[e.selectedIndex].text, 'trim');
            window.location = "vehicle_model.php?" + brand + model + trim;
        }

        function create_param(val, paramName) {
            if (val.substring(0, 2) != '--') {
                return paramName + '=' + val + '&';
            }
            return '';
        }
        <script type="text/javascript">
    function validate() {
        var brand = document.getElementById("brand").value;
        var model = document.getElementById("model").value;
        var trim = document.getElementById("trim").value;
        if (brand == null || brand == "" || model == null || model == "" || trim == null || trim == "") {
            bootbox.alert("All fields are mandatory");
            return false;
        }
        return true;
    }
</script>
    </script>
</head>
<body>
<!-- Bootstrap core JavaScript -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="../js/bootstrap.js"></script>
<script src="../js/bootbox.min.js"></script>

<script type="text/javascript">

    function validate_delete(name, mid) {
        bootbox.confirm("Delete model : " + name + "?", function(result) {
            if (result) {
                window.location = 'vehicle_model.php?delete=' + mid;
            }
        });
    }

</script>

<div id="wrapper">

    <?php
    include('../login/session_check.php');
    include('../db_connector.php');
    include('../constants.php');
    $activePage = 'model';
    include('../sidebar.php');
    ?>

    <div id="page-wrapper">

        <div class="container">
            <table style="width: 100%">
                <tr>
                    <td><h3>Vehicle Models Serviced at Auto Shop</h3></td>
                    <td align="right"><a href="add_vehicle_model.php">Add New Vehicle Model</a></td>
                </tr>
            </table>
            <br/>

            <?php
            $query = "select distinct brand from vehicle_model";
            $result = mysql_query($query);
            $num = mysql_numrows($result);
            ?>

            <table width="80%">
                <tr>
                    <td>
                        <label>
                            <select class="form-control" style="width: 120px" id="select_brand" onchange="get_models()">
                                <option selected="selected" disabled="disabled">-- Brand --</option>
                                <?php
                                for ($i = 0; $i < $num; $i++) {
                                    $brand = mysql_result($result, $i, 'brand');
                                    echo "<option>$brand</option>";
                                }
                                ?>
                            </select>
                        </label>
                    </td>
                    <td>
                        <label>
                            <select class="form-control" style="width: 120px" id="select_model" disabled="disabled"
                                    onchange="get_trims()">
                                <option selected="selected" disabled="disabled">-- Model --</option>
                            </select>
                        </label>
                    </td>
                    <td>
                        <label>
                            <select class="form-control" style="width: 120px" id="select_trim" disabled="disabled">
                                <option selected="selected" disabled="disabled">-- Trim --</option>
                            </select>
                        </label>
                    </td>
                    <td>
                        <button type="button" onclick="search()" class="btn btn-primary">Search
                        </button>
                    </td>
                </tr>
            </table>
            <br/>
        </div>

        <div class="container">

            <?php

            $modelToDelete = htmlspecialchars($_GET["delete"]);
            if ($modelToDelete != null) {
                $del_query = "delete from vehicle_model where mid=$modelToDelete";
                mysql_query($del_query);
            }

            $brandToSearch = htmlspecialchars($_GET["brand"]);
            $modelToSearch = htmlspecialchars($_GET["model"]);
            $trimToSearch = htmlspecialchars($_GET["trim"]);

            $whereCond = '';
            if ($brandToSearch != null) {
                $whereCond .= "brand='" . $brandToSearch . "' ";
            }
            if ($modelToSearch != null) {
                $whereCond .= "and model='" . $modelToSearch . "'";
            }
            if ($trimToSearch != null) {
                $whereCond .= "and trim='" . $trimToSearch . "'";
            }

            if ($whereCond == '') {
                $query = "select * from vehicle_model ";
            } else {
                $query = "select * from vehicle_model where $whereCond";
            }
            $result = mysql_query($query);

            $num = mysql_numrows($result);

            echo "<table class='table'><tr><th>Brand</th><th>Model</th><th>Trim</th><th>Actions</th>";

            for ($i = 0; $i < $num; $i++) {
                echo "<tr>";
                $mid = mysql_result($result, $i, 'mid');
                $brand = mysql_result($result, $i, 'brand');
                $model = mysql_result($result, $i, 'model');
                $trim = mysql_result($result, $i, 'trim');
                $del_model = $brand.'/'.$model.'/'.$trim;
                echo "<td>$brand</td> <td>$model</td> <td>$trim</td>
    <th><a href='update_vehicle_model.php?mid=$mid' >edit</a>
    <a href='#' onclick=\"validate_delete('$del_model', '$mid')\" >delete</a></th>";
                echo "</tr>";
            }

            echo "</table>";
            ?>
        </div>

    </div>
</div>

</body>
</html>