  <?php
		include ('../login/session_check.php');
		include ('../db_connector.php');
		include ('../constants.php');
		$activePage = 'ticket';
		include ('../sidebar.php');
		?>
<html lang="en">
<head>
<link href="../bootstrap/css/bootstrap.css" rel="stylesheet"
	media="screen">
<!-- Add custom CSS here -->
<link href="../css/sb-admin.css" rel="stylesheet">
<link rel="stylesheet" href="../font-awesome/css/font-awesome.min.css">
    <title>Auto Repair Shop</title>
</head>
<body>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="../js/bootstrap.js"></script>
<script src="../js/bootbox.min.js"></script>
<script type="text/javascript">
    function getQuantity() {
        var e = document.getElementById("select_part");
        var pid = e.options[e.selectedIndex].value;
        //alert (pid);
        if(pid != -1){
        $.ajax({
            type:"POST",
            url:"getPartQuantity.php",
            data:{pid:pid}
        }).done(function (result) {
                var data_array = $.parseJSON(result);
                e = document.getElementById("select_qty");
                var count = $('#select_qty option').size();
                // remove all except 1st element -- Model --
                for (i = 1; i < count; i++) {
                    e.remove(1);
                }

                for (i = 1; i <= data_array[0]; i++) {
                    var option = document.createElement("option");
                    option.text = i;
                    option.value = i;
                    e.appendChild(option);
                    //e.add(option, null);
                }
                e.options[0].selected = true;
                e.disabled = false;
            });
        }
        else {
        	e = document.getElementById("select_qty");
            var count = $('#select_qty option').size();
            // remove all except 1st element -- Model --
            for (i = 1; i < count; i++) {
                e.remove(1);
            }
            e.options[0].selected = true;
            e.disabled = true;
        }
    }
    function validate(){
        var e = document.getElementById("select_part");
        var pid = e.options[e.selectedIndex].value;
        var comment = document.getElementById("comment").value;
        if(pid != -1){
            var e1 = document.getElementById("select_qty");
            var qty = e1.options[e1.selectedIndex].value;
            if(qty == -1){
                bootbox.alert ("Select Part Quantity");
                return false;
            }
            else {
                return true;
            }
        } else {
            if (comment == '') {
                bootbox.alert ("Enter either comment or Part");
                return false;
            } else {
                if (!isValid(comment)) {
                    bootbox.alert("No special characters (~`!#$%\^&*+=\-\[\]\';\/{}|\":<>\?) are allowed on comments !");
                    return false;
                }
                return true;
            }
        }
    }

    // check strings for special characters
    function isValid(str){
        return !/[~`!#$%\^&*+=\[\]\\';\/{}|\\":<>\?]/g.test(str);
    }

    function cancel(url) {
        window.location = url;
    }
</script>
<!-- Bootstrap core JavaScript -->
	<script
		src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="../js/bootstrap.js"></script>
	<script src="../js/bootbox.min.js"></script>

	<div id="wrapper">



		<div class="container">

        <?php
								$ticketId = htmlspecialchars ( $_GET ["tid"] );
								$_SESSION ['tid'] = $ticketId;
								
								if ($ticketId != null) {
									$query = "select * from ticket where tid=$ticketId";
									$result = mysql_query ( $query );
									
									$num = mysql_numrows ( $result );
									if ($num > 0) {
										$ticketSummary = mysql_result ( $result, 0, 'summary' );
										
										$parts_query = "select * from part where in_stock > 0;";
										$parts_result = mysql_query ( $parts_query );
										$parts_num = mysql_numrows ( $parts_result );
										
										echo "
                        <h3>Add Comment on Ticket</h3>
                        <br>
                        <form class='form-horizontal' role='form' name='commentForm' action='ticket_details.php?comment=true&tid=$ticketId' method='post'>
                            <div class='form-group'>
                                <label class='col-md-2 control-label'>Ticket ID</label>
                                <div class='col-md-2'>
                                    <p class='form-control-static'>$ticketId</p>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label class='col-md-2 control-label'>Summary</label>
                                <div class='col-md-7'>
                                    <p class='form-control-static'>$ticketSummary</p>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label for='comment' class='col-md-2 control-label'>Comment</label>
                                <div class='col-md-8'>
                                    <textarea rows='8' class='form-control' id='comment' name='formComment'
                              placeholder='Write comment here'></textarea>
                                </div>
                            </div>
 
                
                			<div class='form-group'>
                    			<label class='col-md-2 control-label'>Part</label>
                   				 <div class='col-md-2'>
                        		<select class='form-control' style='width: 190px' id='select_part' name='select_part' onchange='getQuantity()'>
                          		<option selected='selected' value='-1'>-- Select Part --</option>";
										if ($parts_num > 0) {
											for($i = 0; $i < $parts_num; $i ++) {
												$pid = mysql_result ( $parts_result, $i, 'pid' );
												$description = mysql_result ( $parts_result, $i, 'description' );
												$brand = mysql_result ( $parts_result, $i, 'brand' );
												$option = "<option value='$pid'>$brand - $description </option>";
												echo $option;
											}
										}
                                        $cancel_url = $server_host . "autoshop/ticket/ticket_details.php?tid=" . $ticketId;
                       echo "</select>
                		</div>
                    
                </div>
				<div class='form-group'>
                    <label class='col-md-2 control-label'>Quantity</label>
                		<div class='col-md-2'>
                        <select class='form-control' style=' width: 190px' id='select_qty' name='select_qty' disabled='disabled'>
                            <option selected='selected' disabled='disabled' value='-1'>-- Quantity --</option>
                        </select>
                		</div>
                </div>
                            <div class='form-group'>
                                <label class='col-md-2 control-label'></label>
                                <div class='col-md-4'>
                                    <button type='submit' class='btn btn-primary' onclick='return validate()'>Add</button>
                                    <button type='button' onclick=\"cancel('$cancel_url')\" class='btn btn-primary'>Cancel</button>
                                </div>
                            </div>
                        </form>
                    ";
									}
								} else {
									// if the ticket id is null, return to ticket list
									header ( "Location: " . $server_host . "autoshop/ticket/ticket.php" );
									exit ();
								}
								
								?>
    </div>
	</div>
</body>

</html>