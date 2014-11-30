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
        $file = stripslashes($file);
		$decoded_string = json_decode($file);
  		return $decoded_string->myNumber;
  		//checkk userno validity
		
	}
	public static function getActivityObjects($file)
	{
			include 'activity_object.php';
			$objectsArray   = array();
			$file           = stripslashes($file);
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
	{		$idStatus = 0;
			$streamingStatus = 0;
			
			$status =0;
			if( strlen($data->songId)==10 ){
					$idStatus = 1;
					
			}			
			if( ($data->streaming == 1) || ($data->streaming ==0)  ){
			
					$streamingStatus = 1;
					

			}
		
			$status = ($idStatus AND  $streamingStatus) ;
			$_SESSION["logObject"]->debug("file_parser","Data Checker ID Status-- $idStatus");
			$_SESSION["logObject"]->debug("file_parser","Data Checker Streaming Status-- $streamingStatus");
			
			$_SESSION["logObject"]->debug("file_parser","Data Checker Final Status-- $status");
			return $status;


	}



}