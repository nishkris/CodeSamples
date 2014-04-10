<?php
include ('../login/session_check.php');
include ('../../db_connector.php');
include ('../../constants.php');
$activePage = 'ticket';
include ('../sidebar.php');
?>
<html lang="en">
<head>
    <link href="../ui/bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
    <link href="../ui/bootstrap/css/sb-admin.css" rel="stylesheet">
    <link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css">
    <title>Auto Repair Shop</title>
</head>
<body>
<script src="../../js/bootbox.min.js"></script>
<script type="text/javascript">

    function validate() {
        var comment = document.getElementById("comment").value;
        if (comment == '') {
            bootbox.alert("Please enter your comment");
            return false;
        } else {
            if (!isValid(comment)) {
                bootbox.alert("No special characters (~`!#$%\^&*+=\-\[\]\';\/{}|\":<>\?) are allowed on comments !");
                return false;
            }
            return true;
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
<script src="../../js/bootstrap.js"></script>
<script src="../../js/bootbox.min.js"></script>

<div id="wrapper">
    <div class="container">
        <?php
        $ticketId = htmlspecialchars($_GET ["tid"]);
        $_SESSION ['tid'] = $ticketId;

        if ($ticketId != null) {
            $query = "select * from ticket where tid=$ticketId";
            $result = mysql_query($query);

            $num = mysql_numrows($result);
            if ($num > 0) {
                $ticketSummary = mysql_result($result, 0, 'summary');
                $cancel_url = $ext_context . "ticket/ticket_details.php?tid=" . $ticketId;
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
            header("Location: " . $ext_context . "ticket/ticket.php");
            exit ();
        }

        ?>
    </div>
</div>
</body>

</html>