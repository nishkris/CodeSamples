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
</head>
<body>
<!-- Bootstrap core JavaScript -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="../js/bootstrap.js"></script>
<script src="../js/bootbox.min.js"></script>
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
    include('../db_connector.php');
    include('../constants.php');
    $activePage = 'customer';
    include('../sidebar.php');
    ?>

    <div id="page-wrapper">

        <div class="container">
            <h3>Fill Vehicle Information</h3>

            <?php
            $customerId = htmlspecialchars($_GET["custId"]);
            echo "<form role='form' action='db_add_vehicle.php?custId=$customerId' method='post'>";
            ?>

            <div class='form-group'>
                <label for='vin'>VIN</label>
                <input style="width: 250px" type='text' class='form-control' id="vin" name="formVIN"
                       placeholder='Enter VIN number'>
            </div>
            <div class='form-group'>
                <label for='year'>Year</label>
                <input style="width: 200px" type='text' class='form-control' id="year" name="formYear"
                       placeholder='Enter year of manufacture'>
            </div>
            <div class='form-group'>
                <label for='mileage'>Mileage</label>
                <input style="width: 200px" type='text' class='form-control' id='mileage' name="formMileage"
                       placeholder='Enter current mileage'>
            </div>
            <div class='form-group'>
                <?php
                $query = "select distinct brand from vehicle_model";
                $result = mysql_query($query);
                $num = mysql_numrows($result);
                ?>
                <br>
                <table width="80%">
                    <tr>
                        <td>
                            <label>
                                <select class="form-control" style="width: 120px" id="select_brand" name="select_brand"
                                        onchange="get_models()">
                                    <option selected="selected" disabled="disabled">-- Brand --
                                    </option>
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
                                <select class="form-control" style="width: 120px" id="select_model" name="select_model"
                                        disabled="disabled" onchange="get_trims()">
                                    <option selected="selected" disabled="disabled">-- Model --
                                    </option>
                                </select>
                            </label>
                        </td>
                        <td>
                            <label>
                                <select class="form-control" style="width: 120px" id="select_trim" name="select_trim"
                                        disabled="disabled">
                                    <option selected="selected" disabled="disabled">-- Trim --
                                    </option>
                                </select>
                            </label>
                        </td>
                    </tr>
                </table>
                <br>
            </div>
            <button type="submit" onclick="return validate()" class="btn btn-primary">Add</button>
            <button type="button" onclick="history.go(-1);return true;" class="btn btn-primary">
                Cancel
            </button>
            </form>
        </div>
    </div>
</div>

</body>
</html>