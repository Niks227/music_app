<?php
	
	$SQL_SERVER = 'localhost';
	$SQL_USER = 'root';
	$SQL_PASS = '';
	$SQL_DB = 'music_app';

	$con =  new mysqli($SQL_SERVER,$SQL_USER,$SQL_PASS,$SQL_DB);

	if ($con->connect_errno) {
	    echo "Failed to connect to MySQL: (" . $con->connect_errno . ") " . $con->connect_error;
	}
	//echo "Connection ok <Br>";
?>