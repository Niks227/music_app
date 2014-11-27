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
		//echo "Getting user no... <br>";
        $file = stripslashes($file);
		$decoded_string = json_decode($file);
  		return $decoded_string->myNumber;
  		//checkk userno validity
		
	}
	public static function getActivityObjects($file)
	{
			include 'activity_object.php';
			$objectsArray = array();
			$file = stripslashes($file);
  			$decoded_string = json_decode($file);

  			foreach($decoded_string->userActivity as $data){
			 		$objectsArray[] = new activity_object($data->postId,$data->songId,$data->action,$data->streaming,$data->ts);
					$_SESSION["logObject"]->debug("file_parser","Creating new activity object");
					$_SESSION["logObject"]->debug("file_parser","Post id- $data->postId");
					$_SESSION["logObject"]->debug("file_parser","Song id- $data->songId");
					$_SESSION["logObject"]->debug("file_parser","Action - $data->action");
					$_SESSION["logObject"]->debug("file_parser","Streaming- $data->streaming");
					$_SESSION["logObject"]->debug("file_parser","Timestamp- $data->ts");	            
            } 
           	return $objectsArray;		
		

	}


}