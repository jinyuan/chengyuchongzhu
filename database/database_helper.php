<?php

require_once('../database/configuration.php');
require_once('../error/error_handler.php');

class DatabaseHelper {

	var $connection_status = 0;
	var $query_results = 0;
	var $current_row = 0;
	var $record = array();
	
	var $error_number = 0;
	var $error = "";
	
	function connect() {
		if ($this->connection_status == 0) { $this->connection_status = mysql_connect(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD); }
		if (!$this->connection_status) { on_error(DATABASE_ERROR, "Unable to connect to database"); }
		if (!mysql_query(sprintf("use %s", DATABASE_NAME), $this->connection_status)) { on_error(DATABASE_ERROR, "Unable to use database: " . DATABASE_NAME); }
	}
	
	function connection() {
		return $this->connection_status;
	}
	
	function query($query_string) { 
       $this->connect(); 
       $this->query_results = mysql_query($query_string, $this->connection_status); 
       $this->current_row = 0; 
       $this->error_number = mysql_errno(); 
       $this->error = mysql_error(); 
		
       if(!$this->query_results) { on_error(DATABASE_ERROR, "Invalid SQL: " . $query_string ); }
       return $this->query_results; 
	}
	
	function nextRecord() {
		@ $this->record = mysql_fetch_array($this->query_results);
		$this->row += 1;
		$this->error_no = mysql_errno();
		$this->error = mysql_error();
		
		$stat = is_array($this->record);
		if(!$stat) { @ mysql_free_result($this->query_results); $this->query_results = 0; }
		
		return $stat;
	}
	
	function singleRecord() {
		$this->record = mysql_fetch_array($this->query_results);
		$stat = is_array($this->record);
		
		return $stat;
	}
	
    function numRows() { 
       	return mysql_num_rows($this->query_results); 
    }
	
	function numRowsAffected() {
		return mysql_affected_rows($this->connection_status);
	}
	
	function close() {
		if(is_resource($this->connection_status)) { mysql_close($this->connection_status); }
	}
}

?>