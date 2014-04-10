<?php
    include('../login/session_check.php');
    include('../db_connector.php');
    include('../constants.php');
    $activePage = 'model';
    include('../sidebar.php');
    ?>
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
    function validate() {
        var brand = document.getElementById("brand").value;
        var model = document.getElementById("model").value;
        var trim = document.getElementById("trim").value;
        //var mileage = document.getElementById("mileage").value;
        if (brand == "" || brand == null) {
            bootbox.alert("Brand must be entered!");
            return false;
        } else if (model == "" || model == null) {
            bootbox.alert("Model must be entered!");
            return false;
        } else if (trim == "" || trim == null) {
            bootbox.alert("Trim must be entered!");
            return false;
        }

        if (!isValid(brand) || !isValid(model) || !isValid(trim)) {
            bootbox.alert("No special characters (~`!#$%\^&*+=\-\[\]\';\/{}|\":<>\?) are allowed on any of the fields !");
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

    

    <div id="page-wrapper">

        <div class="container">
            <h3>Fill Vehicle Model Information</h3>

            <form role='form' action='db_add_vehicle_model.php' method='post' onsubmit='return validate()'>
                <div class='form-group'>
                    <label for='brand'>Brand</label>
                    <input type='text' style="width: 250px" class='form-control' id='brand' name="formBrand"
                           placeholder='Enter Vehicle Brand, Ex : Toyota'>
                </div>
                <div class='form-group'>
                    <label for='model'>Model</label>
                    <input type='text' style="width: 250px" class='form-control' id="model" name="formModel"
                           placeholder='Enter Vehicle Model, Ex : Camry'>
                </div>
                <div class='form-group'>
                    <label for='trim'>Trim</label>
                    <input type='text' style="width: 250px" class='form-control' id="trim" name="formTrim"
                           placeholder='Enter Trim, Ex: XLE'>
                </div>
                <button type="submit" class="btn btn-primary">Add</button>
                <button type="button" onclick="history.go(-1);return true;" class="btn btn-primary">
                    Cancel
                </button>

            </form>
        </div>
    </div>
</div>

</body>
</html>