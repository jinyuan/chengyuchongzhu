<?php

define('DATABASE_ERROR', 'Database Error: ');
define('C2DM_ERROR', 'C2DM Error: ');
define('FUNCTION_ERROR', 'Function Error: ');
define('ACCOUNT_AUTHENTICATION_ERROR', 'Account Authentication Error: ');
define('USER_ERROR', 'User Error: ');
define('UNKNOWN_ERROR', 'Unknown Error: ');

set_error_handler('custom_error');

function on_error($error_type, $message) {
	die($error_type . ' ' . $message);
}

function custom_error($error_code, $message) {
	on_error($error_code, $message);
}

?>