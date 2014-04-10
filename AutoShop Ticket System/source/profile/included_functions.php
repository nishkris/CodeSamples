<?php


/*****************************************************************************************
 ** http://www.lynda.com/MySQL-tutorials/Single-page-form-validations/119003/136992-4.html
 * Credit for  Kevin Skoglund, reference his course  
 * PHP with MySQL Essential Training with Kevin Skoglund 
 * cite his code from exercise file
 *****************************************************************************************/

	function hello($name) {
		return "Hello {$name}!";
	}
	
	function redirect_to($new_location) {
		header("Location: " . $new_location);
		exit;
	}
?>
