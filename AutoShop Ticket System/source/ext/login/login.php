<!DOCTYPE html>
<meta charset=”utf-8”> 
<?php 
include "../../db_connector.php";
include "../../constants.php";

function check($username, $password) {
	if (!($username && $password))
		return false;
	$result = mysql_query("SELECT * FROM customer WHERE username='$username';") or die(mysql_error());
	if(mysql_num_rows($result) != 1) 
	{
		$_SESSION['Msg']="Invalid Username/Password. Please Try again.";
		return false;
	}
    $hashed_pw = mysql_result($result, 0, 'password');
    if(crypt($password, $hashed_pw) != $hashed_pw) 
    {
        $_SESSION['Msg']="Invalid Username/Password. Please Try again.";
		return false;
	}
	session_set_cookie_params(1200, '/');
	session_start();
	$_SESSION['username'] = $username;
    global $role_customer;
    $_SESSION['role'] = $role_customer;
    $cid = mysql_result($result, 0, 'cid');
    $_SESSION["name"] = mysql_result($result, 0, 'name');
    $_SESSION['cid'] = $cid;
    return true;
}

if(check($_POST['username'], $_POST['passwd'])) {
	header("Location: " . $ext_context . "vehicle/vehicle.php");
}

$signup = htmlspecialchars($_GET["signup"]);
$login_msg = "false";
if ($signup != null && $signup == "true") {
    $login_msg = "true";
}
?>
<script type="text/javascript">
    function validate() {
        var name = document.getElementById("name").value;
        var phone = document.getElementById("phone").value;
        var address = document.getElementById("address").value;
        var email = document.getElementById("email").value;
        var user = document.getElementById("user").value;
        var password = document.getElementById("password").value;

        if (name == null || name == "" || phone == null || phone == "" || address == null || address == ""
        || email == null || email == "" || user == null || user == "" || password == null || password == "") {
            bootbox.alert("All fields are mandatory!");
            return false;
        } else if(email.indexOf('@') == -1) {
            bootbox.alert("Email is not valid!");
            return false;
        }
        if (!isValid(name) || !isValid(phone) || !isValid(address) ||
            !isValid(email) || !isValid(user) || !isValid(password)) {
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
<style>
footer.footer.navbar-fixed-top {
top: auto;
bottom: 0;
}
</style>
<html>
	<head>
	<link href="../ui/bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
	<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
	<script src="../../bootstrap/js/bootstrap.js"></script>
	<script src="../../js/bootbox.min.js"></script>
        <script type="text/javascript">
            $(function() {
                if(<?php echo $login_msg; ?>) {
                    bootbox.alert("Successfully Registered, please sign in!");
                }
            });
        </script>
	<link href='http://fonts.googleapis.com/css?family=Roboto+Condensed' rel='stylesheet' type='text/css'>
		<title>
			Login
		</title>
    </head>
    <body>
    <style>
        body{  margin:0; font-family: 'Roboto Condensed', sans-serif; background-size:100%; }
    </style>
    <div class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header pull-left">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">AutoShop</a>
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right navbar-user">
                    <li class="dropdown">
                        <a class="dropdown-toggle" href="#" data-toggle="dropdown">Sign In <strong class="caret"></strong></a>
                        <div class="dropdown-menu" style="padding: 15px; padding-bottom: 10px;height:200px;width:300px">
                            <form role='form' method="post" action="login.php">
                                <div class='form-group'>
                                    <input type="text" id="username" name="username" maxlength="50" class="form-control"  placeholder="Username"/>
                                </div>
                                <div class='form-group'>
                                    <input type="password" id="passwd" name="passwd" class="form-control"  placeholder="Password"/>
                                </div>
                                <button type='submit' class='btn btn-primary btn-block'>Login</button>
                            </form>
                            <div align="center">Login Issues? Please contact support@autoshop.com</div>
                        </div>
                    </li>
                    <li class="divider-vertical"></li>
                    <li class="dropdown">
                        <a class="dropdown-toggle" href="#" data-toggle="dropdown">Sign Up <strong class="caret"></strong></a>
                        <div class="dropdown-menu" style="padding: 15px; padding-bottom: 10px;height:400px;width:300px">
                            <form role='form' action='db_add_customer.php' method='post' onsubmit="return validate()">
                                <div align="center">
                                    It's Quick and Easy.<br> Just fill this form!
                                </div>
                                <div class='form-group'>
                                    <input type='text' class='form-control' id='name' name="formCustomerName"
                                           placeholder='Enter Name'>
                                </div>
                                <div class='form-group'>
                                    <input type='text' class='form-control' id='phone' name="formCustomerPhone"
                                           placeholder='Enter Phone Number'>
                                </div>
                                <div class='form-group'>
                                    <input type='text' class='form-control' id='address' name="formCustomerAddress"
                                           placeholder='Enter Address'>
                                </div>
                                <div class='form-group'>
                                    <input type='email' class='form-control' id='email' name="formCustomerEmail"
                                           placeholder='Enter Email'>
                                </div>
                                <div class='form-group'>
                                    <input type='text' class='form-control' id='user' name="formCustomerUser"
                                           placeholder='Enter Username'>
                                </div>
                                <div class='form-group'>
                                    <input type='password' class='form-control' id='password'
                                           name="formCustomerPassword"
                                           placeholder='Enter Password'>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block">Register</button>
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="page-header">
            <br>
            <h2 align="center">Its Simple !</h2>
        </div>
        <div class="row clearfix" align="center">
            <div class="col-md-4 column">
                <img src="../ui/images/icons/png/Pensils.png" height=20% />
                <br><h3><strong> Sign Up </strong></h3>
            </div>
            <div class="col-md-4 column">
                <img src="../ui/images/icons/png/Clipboard.png" height=20% />
                <br><h3><strong> Book Appointment </strong></h3>
            </div>
            <div class="col-md-4 column" >
                <img src="../ui/images/icons/png/Retina-Ready.png" height=20% />
                <br><h3><strong> We 'll take care of the rest !</strong></h3>
            </div>

        </div>
    </div>
    <footer class="footer navbar navbar-fixed-top">
		(C) Autoshop 2013
    </footer>
            <script type="text/javascript">
            var inup = "<?php echo $_SESSION['Msg']; ?>";
            if(inup!="")
            {bootbox.alert(inup);}
        </script>
    </body>
</html>
