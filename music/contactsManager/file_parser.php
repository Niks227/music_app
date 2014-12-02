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
			$ERROR_CODE_LEVEL = 2000;
			$ERROR_CODE_ROOM  = 10;
		    $decoded_string = json_decode($file);
			if (!isset($decoded_string->myNumber)){
					$ERROR_NO = 1;
					$errorCode= $ERROR_CODE_LEVEL + $ERROR_CODE_ROOM + $ERROR_NO ;
					$error = new errorHandling($errorCode,"myNumber not Found",$_SESSION["logFileNo"]);
					$error->send();
					return NULL;
					
			}
			else{
				return $decoded_string->myNumber;	
			}
			
	  	}
	
	
	}
?>