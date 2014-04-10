<!DOCTYPE html>
<meta charset=”utf-8”> 
<?php 
include "../db_connector.php";
include "../constants.php";
function check($username, $password)
{
	if (!($username && $password))
		return false;
	$result = mysql_query("SELECT * FROM internal_user WHERE  username='$username';") or die(mysql_error());
	if(mysql_num_rows($result) != 1) 
	{
		$_SESSION['Msg']="Invalid Username/Password. Please Try again.";
		return false;
	}
    $hashed_pw = mysql_result($result, 0, 'password');
    if(crypt($password, $hashed_pw) != $hashed_pw) {
        $_SESSION['Msg']="Invalid Username/Password. Please Try again.";
		return false;
	}
	session_set_cookie_params(1200, '/');
	session_start();
	$_SESSION['username'] = $username;
    $eid = mysql_result($result,0,'eid');
    $role = mysql_result($result,0,'role');
	$_SESSION['eid'] = $eid;
	$_SESSION['role'] = $role;
    $name_result = mysql_query("SELECT name FROM $role WHERE eid='$eid';") or die(mysql_error());
    if(mysql_num_rows($name_result) == 1) {
        $_SESSION['name'] = mysql_result($name_result, 0, 'name');;
    }
	return true;
}
if(check($_POST['username'], $_POST['passwd']))
	{
        header("Location: " . $server_host . "autoshop/dash/dashboard.php");
	}
?> 
<html>
	<head>
	<link href="../bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
	<link href="../bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">
	<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
	<script src="../js/bootbox.min.js"></script>
	<link href='http://fonts.googleapis.com/css?family=Roboto+Condensed' rel='stylesheet' type='text/css'>
		<title>
			Login
		</title>     
	</head>
<body>
<!--background-image: url("bg.jpg");background: rgba(10, 10, 10, 0.5);-->
<style>
body{  margin:0; font-family: 'Roboto Condensed', sans-serif; background-image: url('bg.png'); background-size:100%; }
.login{padding-top: 0%; padding-left:30%;padding-right:0%;}
.center{float: left; margin-left: auto; margin-right: 50%;background: rgba(236, 240, 241,0); margin-top: 20%; padding-top:"20px";border-color: rgba(220, 220, 220,0);box-shadow: 0px 1px 3px rgba(0, 0, 0, 0) inset, 0px 1px 0px rgba(255, 255, 255, 0);}
</style>
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Auto Repair Shop</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
          </ul>
        </div>
      </div>
    </div>
<div class="container">
<div class="container login">
    <div class="row ">
        <div class="center span4 well" align="center" id="glass">
        <img src="logo.svg" width="50%" height="50%"/>
	<br>Please Login
	<form role='form' method="post" action="login.php">
		<div class='form-group'>
		<input type="text" id="username" name="username" maxlength="50" class='form-control'  placeholder="Username"/>
		</div>
		<div class='form-group'>
		<input type="password" id="passwd" name="passwd" class="form-control"  placeholder="Password"/>
		</div>
		<button type='submit' class='btn btn-lg btn-primary btn-block'>Login</button>
	</form>
	<span>Please contact the administrator if you have login issues</span>
	</div>
	</div>
</div>
</div>
       <script type="text/javascript">
            var inup = "<?php echo $_SESSION['Msg']; ?>";
            if(inup!="")
            {bootbox.alert(inup);}
        </script>
</body>
</html>

