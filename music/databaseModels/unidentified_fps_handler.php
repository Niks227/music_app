<?php

/**
* 
*/
class unidentified_fps_handler
{
	
	function __construct()
	{
		
	}
	public static function insert_unidentified_fp($fp_xml, $myNumber)
	{
			include "sqli_connect.php";
			$query = "INSERT INTO `unidentified_fingerprints`(`user_id`, `fingerprint`) VALUES ('$myNumber','$fp_xml')";
			$result = $con->query($query);
		
			include "sqli_close.php";

	}
}
?>