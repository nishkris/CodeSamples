<!DOCTYPE html>
<html lang="en">
<head>
    <link href="../ui/bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
    <link href="../ui/bootstrap/css/sb-admin.css" rel="stylesheet">
    <link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css">
    <title>Auto Repair Shop</title>
    <script src="../../js/vehicle.js"></script>
</head>
<body>

<!-- Bootstrap core JavaScript -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="../../js/bootstrap.js"></script>
<script src="../../js/bootbox.min.js"></script>
<script type="text/javascript">
    function validate() {
        var vin = document.getElementById("vin").value;
        var year = document.getElementById("year").value;
        var mileage = document.getElementById("mileage").value;
        if (vin == null || vin.length != 17) {
            bootbox.alert("VIN must contain 17 characters");
            return false;
        } else if (year == null || year == "" || mileage == null || mileage == "") {
            bootbox.alert("Vehicle year and mileage are mandatory");
            return false;
        } else if (isNaN(year) || isNaN(mileage)) {
            bootbox.alert("Vehicle year and mileage should be numbers");
            return false;
        }
        if (!isValid(vin) || !isValid(year) || !isValid(mileage)) {
            bootbox.alert("No special characters (~`!#$%\^&*+=\-\[\]\';\/{}|\":<>\?) are allowed on any of the fields !");
            return false;
        }
        var brand = document.getElementById("select_brand").selectedIndex;
        var model = document.getElementById("select_model").selectedIndex;
        var trim = document.getElementById("select_trim").selectedIndex;
        if (brand = 0 || model == 0 || trim == 0) {
            bootbox.alert("Vehicle Brand, Model and Trim must be selected!");
            return false;
        }
        return true;
    }

    // check strings for special characters
    function isValid(str){
        return !/[~`!#$%\^&*+=\[\]\\';\/{}|\\":<>\?]/g.test(str);
    }
</script>
<div id="wrapper">

    <?php
    include('../login/session_check.php');
    include('../../db_connector.php');
    include('../../constants.php');
    $activePage = 'vehicle';
    include('../sidebar.php');
    ?>

    <div id="page-wrapper">

        <div class="container">
            <h3>Fill Vehicle Information</h3>

            <?php
            $customerId = htmlspecialchars($_SESSION['cid']);
            $vinToUpdate = htmlspecialchars($_GET["vin"]);
            if ($vinToUpdate != null) {
                $query = "select v.year, v.mileage, m.brand, m.model, m.trim from vehicle v, vehicle_model m where v.model=m.mid and v.vin='$vinToUpdate'";
                $result = mysql_query($query);

                $num = mysql_numrows($result);
                if ($num > 0) {
                    $vehicleYear = mysql_result($result, 0, 'year');
                    $vehicleMileage = mysql_result($result, 0, 'mileage');
                    $vehicleBrand = mysql_result($result, 0, 'brand');
                    $vehicleModel = mysql_result($result, 0, 'model');
                    $vehicleTrim = mysql_result($result, 0, 'trim');

                    $query3 = 'select distinct brand from vehicle_model';
                    $result3 = mysql_query($query3);
                    $num3 = mysql_numrows($result3);

                    echo "<form role='form' action='db_add_vehicle.php?update=true&vin=$vinToUpdate' onsubmit='return validate()' method='post'>
    <div class='form-group'>
        <label for='vin'>VIN</label>
        <input type='text' style='width: 250px' class='form-control' id='vin' name='formVIN' value='$vinToUpdate' disabled='disabled'>
    </div>
    <div class='form-group'>
        <label for='year'>Year</label>
        <input type='text' style='width: 200px' class='form-control' id='year' name='formYear' value='$vehicleYear'>
    </div>
    <div class='form-group'>
        <label for='phone'>Mileage</label>
        <input type='text' style='width: 200px' class='form-control' id='mileage' name='formMileage' value='$vehicleMileage'>
    </div>
    <div class='form-group'>
        <br>
        <table width='80%'>
            <tr>
                <td>
                    <label>
                        <select class='form-control' style='width: 120px' id='select_brand' name='select_brand' onchange='get_models()'>
                            <option disabled='disabled'>-- Brand --</option>";
                        for ($i = 0; $i < $num3; $i++) {
                            $brand = mysql_result($result3, $i, 'brand');
                            if ($brand == $vehicleBrand) {
                                echo "<option selected='selected'>$brand</option>";
                            } else {
                                echo "<option>$brand</option>";
                            }
                        }
                        echo        "</select>
                    </label>
                </td>
                <td>
                    <label>
                        <select class='form-control' style='width: 120px' id='select_model' name='select_model' onchange='get_trims()'>
                            <option disabled='disabled'>-- Model --</option>
                            <option selected='selected'>$vehicleModel</option>
                        </select>
                    </label>
                </td>
                <td>
                    <label>
                        <select class='form-control' style='width: 120px' id='select_trim' name='select_trim'>
                            <option disabled='disabled'>-- Trim --</option>
                            <option selected='selected'>$vehicleTrim</option>
                        </select>
                    </label>
                </td>
            </tr>
        </table>
        <br>
    </div>
    <button type='submit' class='btn btn-primary'>Update</button>
    <button type='button' onclick='history.go(-1);return true;' class='btn btn-primary'>Cancel</button>
    </form>";
                }
            }
            ?>
        </div>
    </div>
</div>

</body>
</html>