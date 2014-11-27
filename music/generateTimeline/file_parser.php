<?php
	/**
	* 
	*/
	class file_parser
	{
		
		function __construct()
		{
		}
		public static function get_user_no($file)
		{	
		//	echo "Getting user no... <br>";
            $file = stripslashes($file);   
			$decoded_string = json_decode($file);
	  		return $decoded_string->myNumber;
	  		//checkk userno validity
			
		}
	}
?>