<?php

require_once('../error/error_handler.php');

if(isset($_GET['f'])) { if(function_exists($_GET['f'])) { $_GET['f'](); } else { on_error(FUNCTION_ERROR, 'Function Not Found - ' . $_GET['f'] . '()' ); } }

?>