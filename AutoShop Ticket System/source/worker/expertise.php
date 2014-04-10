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
    function cancel(url) {
        window.location = url;
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

    <div class="container">

        <?php
        $workerId = htmlspecialchars($_GET["eid"]);
        session_start();

        if ($workerId != null) {
            $name_query = "select name from worker where eid='$workerId';";
            $name_result = mysql_query($name_query);
            if (!$name_result) {
                echo "<p>There is a problem with the query: " . mysql_error() . "</p>";
                exit;
            }
            $name = mysql_result($name_result, 0, 'name');

            // get current expertise
            $query = "select vm.brand, vm.model, vm.trim from expertise e, vehicle_model vm where e.model=vm.mid and e.worker='$workerId';";
            $result = mysql_query($query);
            if (!$result) {
                echo "<p>There is a problem with the query: " . mysql_error() . "</p>";
                exit;
            }
            $num = mysql_numrows($result);
            $current_exp = array();
            for ($i = 0; $i < $num; $i++) {
                $brand = mysql_result($result, $i, 'brand');
                $model = mysql_result($result, $i, 'model');
                $trim = mysql_result($result, $i, 'trim');
                $current_exp[$i] = $brand." / ".$model." / ".$trim;
            }

            // get all models
            $all_query = "select * from vehicle_model order by brand";
            $all_result = mysql_query($all_query);
            if (!$all_result) {
                echo "<p>There is a problem with the query: " . mysql_error() . "</p>";
                exit;
            }
            $all_num = mysql_numrows($all_result);
            if (is_manager()) {
                echo "<h3>Worker Expertise</h3>";
            } else if (is_worker()) {
                echo "<h3>My Expertise</h3>";
            }

                echo"
                        <br>
                        <form class='form-horizontal' role='form' action='db_add_expertise.php?eid=$workerId' method='post'>
                            <div class='form-group'>
                                <label class='col-md-2 control-label'>Employee ID</label>
                                <div class='col-md-2'>
                                    <p class='form-control-static'>$workerId</p>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label class='col-md-2 control-label'>Name</label>
                                <div class='col-md-4'>
                                    <p class='form-control-static'>$name</p>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label class='col-md-2 control-label'>Expertise</label>
                                <div class='col-md-4'>";
            for ($i = 0; $i < $all_num; $i++) {
                $brand = mysql_result($all_result, $i, 'brand');
                $model = mysql_result($all_result, $i, 'model');
                $trim = mysql_result($all_result, $i, 'trim');
                $item = $brand." / ".$model." / ".$trim;

                if (in_array($item, $current_exp)) {
                    echo "
                           <div class='checkbox'>
                                 <input type='checkbox' checked=true id='expertise' name='formExp[]' value='$item'>$item</input>
                           </div>
                         ";

                } else {
                    echo "
                           <div class='checkbox'>
                                <input type='checkbox' id='expertise' name='formExp[]' value='$item'>$item</input>
                           </div>
                         ";
                }
            }

            $cancel_url = $server_host . "autoshop/worker/worker.php";
            if (is_manager()) {
                echo "
                                </div>
                            </div>
                            <div class='form-group'>
                                <div class='col-md-2'></div>
                                <div class='col-md-4'>
                                    <button type='submit' class='btn btn-primary'>Done</button>
                                    <button type='button' onclick=\"cancel('$cancel_url')\" class='btn btn-primary'>Cancel</button>
                                </div>
                            </div>
                        </form>
                    ";
            } else if (is_worker()) {
                echo "
                                </div>
                            </div>
                            <div class='form-group'>
                                <div class='col-md-2'></div>
                                <div class='col-md-4'>
                                    <button type='submit' class='btn btn-primary'>Update Expertise</button>
                                </div>
                            </div>
                        </form>
                    ";
            }
        }
        else {
            // if the ticket id is null, return to ticket list
            header("Location: " . $server_host . "autoshop/worker/worker.php");
            exit();
        }

        ?>
    </div>
</div>
</body>

</html>