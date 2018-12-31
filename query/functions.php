<?php

require_once('../error/error_handler.php');
require_once('../database/database_helper.php');
require_once('../function/data_writer.php');
require_once('../function/function_map.php');

function getRandomChengYus() {
	
	global $error;
	
	$variables = func_get_args();
	$amount = mysql_escape_string(assignInput($_POST['amount'], $variables[0], 'numeric', 1, 255));
	$level = mysql_escape_string(assignInput($_POST['level'], $variables[1], 'numeric', 1, 255));
	
	$db = new DatabaseHelper;
	$posts = array(); $parent_tag = 'chengyus'; $child_tag = 'chengyu'; $error = '';
	
	$statement = "SELECT HEX(chengyu) chengyu FROM chengyu ";
	if($level == 1) { $statement .= "WHERE _id >= 1 AND _id <= 107 "; }
	else if ($level == 2) { $statement .= "WHERE _id >= 108 AND _id <= 128"; }
	else if ($level == 3) { $statement .= "WHERE _id >= 129 AND _id < 250"; }
	$statement .= " ORDER BY RAND() LIMIT 1," . $amount;
	
	$db->query($statement); 
	
	$results = $db->query($statement);
	
	 if(mysql_num_rows($results)) {
		 for ($i=1; $i<=mysql_num_rows($results); $i++)  { 
		 	$post = mysql_fetch_assoc($results);
			echo hex2str($post['chengyu']); if($i != mysql_num_rows($results)) { echo ','; }
		 }
	} else { $error = 'Unknown Error - Database Retrieval Fail!.'; }
}

function hex2str($hex){
    $string='';
    for ($i=0; $i < strlen($hex)-1; $i+=2){
        $string .= chr(hexdec($hex[$i].$hex[$i+1]));
    }
    return $string;
}

function validateInput(&$input, $type, $min_length, $max_length, $nullable) {
		
	$to_be_checked = true;
	
	if($min_length < 0) { error('Minimum length must be higher by then 0'); return false; }
	if($min_length > $max_length) { error('Minimum length is lower then Maximum length.'); return false; }
	
	if($nullable) { if(!isset($input)) { $to_be_checked = false; } } else { if(!isset($input)) { error('Variable is not nullable'); return false; }}
	
	if($to_be_checked) {
		if(!strcasecmp($type, 'email')) { 
			if(preg_match('/^\S+@[\w\d.-]{2,}\.[\w]{2,6}$/iU', $input) == false) { error('Invalid Email Address - ' . $input); return false; }
			} else {
				$type = 'is_' . $type;
				if(!$type($input)) { error('Invalid Data Type Conversion - "' . $input . '" to ' . $type); return false; }
			}

		if(strlen($input) < $min_length || strlen($input) > $max_length) { error('Variable Minimum/Maximum length not matched - ' . $input); return false; }
		if($min_length > 0) { if(empty($input)) { error($input . 'Minimum length is ' . $min_length . ' but variable is empty.');  return false; } }
	}
	
	return true;
}

function assignInput(&$default_input, &$input, $type, $min_length, $max_length) {
	
	global $error;
	
	$validations = array( validateInput($default_input, $type, $min_length, $max_length, true),
						validateInput($input, $type, $min_length, $max_length, true));
	if (in_array(false, $validations)) { on_error(ACCOUNT_AUTHENTICATION_ERROR, $error); }
	
	if(!isset($default_input) && !isset($input)) { on_error(ACCOUNT_AUTHENTICATION_ERROR, 'Both inputs are null!'); }
	else if(!isset($input)) { return $default_input; } else { return $input; }		
}

function error($error_message) {
	global $error;
	$error .= (empty($error) ? $error_message : ', ' . $error_message);
}

?>