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
        var type = document.getElementById("type").value;
        var price = document.getElementById("price").value;
        var initial_stock = document.getElementById("initial_stock").value;

        if (brand == null || brand == "" || type == null || type == "" || price == null || price == ""
        || initial_stock == null || initial_stock == "") {
            bootbox.alert("All fields are mandatory!");
            return false;
        }
        else if(isNaN(price) || isNaN(initial_stock)){
                    bootbox.alert("Price, in stock and total stock are not valid!");
                    return false;
        }

        if (!isValid(brand) || !isValid(type) || !isValid(price) || !isValid(initial_stock)) {
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

    <?php
    include('../login/session_check.php');
    include('../db_connector.php');
    include('../constants.php');
    $activePage = 'part';
    include('../sidebar.php');
    ?>

    <div id="page-wrapper">
        <div class="container">
            <h3>Fill Part Information</h3>

            <form role='form' action='addPart.php' method='post' onsubmit='return validate()'>
                <div class='form-group'>
                    <label for='brand'>Brand</label>
                    <input type='text' class='form-control' id='brand' name="formPartBrand"
                           placeholder='Enter Brand'>
                </div>
                <div class='form-group'>
                    <label for='type'>Type</label>
                    <input type='text' class='form-control' id='type' name="formPartType"
                           placeholder='Enter Type'>
                </div>
                <div class='form-group'>
                    <label for='description'>Description</label>
                    <input type='text' class='form-control' id='description'
                           name="formPartDescription"
                           placeholder='Enter Description'>
                </div>
                <div class='form-group'>
                    <label for='price'>Price</label>
                    <input type='text' style='width: 150px' class='form-control' id='price' name="formPartPrice"
                           placeholder='Enter Price'>
                </div>
                <div class='form-group'>
                    <label for='initial_stock'>Initial Stock</label>
                    <input type='text' style='width: 150px' class='form-control' id='initial_stock'
                           name="formPartInitial_stock"
                           placeholder='Enter Number of Parts'>
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

