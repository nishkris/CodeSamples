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
        var in_stock = document.getElementById("in_stock").value;
        var total_stock = document.getElementById("total_stock").value;
        if (brand == null || brand == "" || type == null || type == "" || price == null || price == ""
        || in_stock == null || in_stock == "" || total_stock == null || total_stock == "") {
            bootbox.alert("All fields are mandatory!");
            return false;
        }
        else if(isNaN(price) || isNaN(in_stock) || isNaN(total_stock)){
                    bootbox.alert("Price, in stock and total stock are not valid!");
                    return false;
        }

        if (!isValid(brand) || !isValid(type) || !isValid(price) || !isValid(in_stock) || !isValid(total_stock)) {
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
            <h3>Edit Part Information</h3>

            <?php
            include "../db_connector.php";
            include "../constants.php";

            $partToUpdate = htmlspecialchars($_GET["partID"]);
            if ($partToUpdate != null) {
                $query = "select * from part where pid=$partToUpdate";
                $result = mysql_query($query);

                $num = mysql_numrows($result);
                if ($num > 0) {
                    $partBrand = mysql_result($result, 0, 'brand');
                    $partType = mysql_result($result, 0, 'type');
                    $partDescription = mysql_result($result, 0, 'description');
                    $partPrice = mysql_result($result, 0, 'price');
                    $partIn_stock = mysql_result($result, 0, 'in_stock');
                    $partTotal_stock = mysql_result($result, 0, 'total_stock');

                    echo "
<form role='form' action='editPart_db.php?partID=$partToUpdate' onSubmit='return validate()' method='post'>
        <div class='form-group'>
            <label for='brand'>Brand</label>
            <input type='text' class='form-control' id='brand' name='formPartBrand'
                   value='$partBrand'>
        </div>
        <div class='form-group'>
            <label for='type'>Type</label>
            <input type='text' class='form-control' id='type' name='formPartType'
                   value='$partType'>
        </div>
        <div class='form-group'>
            <label for='description'>Description</label>
            <input type='text' class='form-control' id='description' name='formPartDescription'
                   value='$partDescription'>
        </div>
        <div class='form-group'>
            <label for='price'>Price</label>
            <input type='text' class='form-control' id='price' name='formPartPrice'
                   value='$partPrice'>
        </div>
        <div class='form-group'>
            <label for='in_stock'>In Stock</label>
            <input type='text' style='width: 150px' class='form-control' id='in_stock' name='formPartIn_stock'
                   value='$partIn_stock'>
        </div>
        <div class='form-group'>
            <label for='total_stock'>Total Stock</label>
            <input type='text' style='width: 150px' class='form-control' id='total_stock' name='formPartTotal_stock'
                   value='$partTotal_stock'>
        </div>
        <button type='submit' class='btn btn-primary'>Update</button>
        <button type='button' onclick='history.go(-1);return true;' class='btn btn-primary'>Cancel</button>
</form>
";

                }
            } else {
                header("Location: " . $server_host . "autoshop/part/showPart.php");
                exit();
            }

            ?>
        </div>
    </div>
</div>

</body>
</html>
