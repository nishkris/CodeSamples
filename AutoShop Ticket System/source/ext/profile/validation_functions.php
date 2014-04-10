<?php


/*****************************************************************************************
 ** http://www.lynda.com/MySQL-tutorials/Single-page-form-validations/119003/136992-4.html
 * Credit for  Kevin Skoglund, reference his course  
 * PHP with MySQL Essential Training with Kevin Skoglund 
 * cite his code from exercise file
 *****************************************************************************************/

// * presence
// use trim() so empty spaces don't count
// use === to avoid false positives
// empty() would consider "0" to be empty
function has_presence($value) {
	return isset($value) && $value !== "";
}

// * string length
// max length
function has_max_length($value, $max) {
	return strlen($value) <= $max;
}

// * inclusion in a set
function has_inclusion_in($value, $set) {
	return in_array($value, $set);
}
function validate_max_lengths($fields_with_max_lengths) {
	global $errors;
	// Expects an assoc. array
	foreach($fields_with_max_lengths as $field => $max) {
		$value = trim($_POST[$field]);
	  if (!has_max_length($value, $max)) {
	    $errors[$field] = ucfirst($field) . " is too long";
	  }
	}
}

function validate_min_lengths($fields_with_min_lengths) {
	global $errors;
	// Expects an assoc. array
	foreach($fields_with_max_lengths as $field => $max) {
		$value = trim($_POST[$field]);
	  if (!has_min_length($value, $max)) {
	    $errors[$field] = ucfirst($field) . " is too long";
	  }
	}
}


function form_errors($errors=array()) {
	$output = "";
	if (!empty($errors)) {
	  $output .= "<div class=\"error\">";
	  $output .= "Please fix the following errors:";
	  $output .= "<ul>";
	  foreach ($errors as $key => $error) {
	    $output .= "<li>{$error}</li>";
	  }
	  $output .= "</ul>";
	  $output .= "</div>";
	}
	return $output;
}

function validate_phone($phone){
	return preg_match("/^\d{10}|\(\d{3}\)-\d{3}-\d{4}$/", $phone);
}

function validate_email($email){
	return preg_match("/^[\w+%.\-]+@[\w\-.]+\.[A-Za-z]{2,6}$/", $email);
}

function validate_name($name){
	return preg_match("/^[A-Za-z.'\- ]+$/", $name);
}

function validate_address($address){
	return preg_match("/^[A-Za-z.,'\- 0-9]+$/", $address);
}

function validate_password($password){
	return preg_match("/^(?=.*\d)(?=.*[A-Za-z])\S{6,15}$/", $password);
}


function check_validation($username, $password){
	if (!($username && $password))
        return false;
    $result = mysql_query("SELECT * FROM customer WHERE  username='$username';") or die(mysql_error());
    if(mysql_num_rows($result) != 1) 
    {
        $_SESSION['Msg']="Invalid user. Please Try again.";
        return false;
    }
    $hashed_pw = mysql_result($result, 0, 'password');
    if(crypt($password, $hashed_pw) != $hashed_pw) {
        $_SESSION['Msg']="Invalid password. Please Try again.";
        return false;
    }
    return true;
}

?>