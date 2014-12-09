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
		$ERROR_CODE_ROOM  = 40;
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
	public static function getActivityObjects($file)
	{
			include 'activity_object.php';
			$objectsArray   = array();
			$decoded_string = json_decode($file);
			foreach($decoded_string->userActivity as $data){
			 		

  					$dataStatus = file_parser::dataChecker($data);
			 		if($dataStatus == 1){
			 			$objectsArray[] = new activity_object($data->postId,$data->songId,$data->action,$data->streaming,$data->ts);
						$_SESSION["logObject"]->debug("file_parser","Creating new activity object");
						$_SESSION["logObject"]->debug("file_parser","Post id- $data->postId");
						$_SESSION["logObject"]->debug("file_parser","Song id- $data->songId");
						$_SESSION["logObject"]->debug("file_parser","Action - $data->action");
						$_SESSION["logObject"]->debug("file_parser","Streaming- $data->streaming");
						$_SESSION["logObject"]->debug("file_parser","Timestamp- $data->ts");	            
            		}
            } 
           	return $objectsArray;		
		

	}
	public static function dataChecker($data)
	{		
			$dataStatus      = 0;
			
			if($data->streaming == 1) {
				$dataStatus = 1;\\Assuming streaming to be true always temporary solution	
				$_SESSION["logObject"]->debug("file_parser","Online Streaming detected");
			}
			elseif ($data->streaming == 0) {
				$_SESSION["logObject"]->debug("file_parser","Streaming 0 detected");
				if( strlen($data->songId)==10 ){
					$_SESSION["logObject"]->debug("file_parser","song Id length valid (10)");
					$dataStatus = 1;
				}
			}
			$_SESSION["logObject"]->debug("file_parser","Data Checker Final Status-- $dataStatus");
			return $dataStatus;
	}

}