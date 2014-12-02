<?php	
session_start();
include 'generateLog.php';
include 'errorHandling.php';

$log = new generateLog();

$_SESSION["logObject"] = $log;
$LOG_FILE_NO = $_SESSION["logObject"]->getLatestLogFile();
$_SESSION["logFileNo"] = $LOG_FILE_NO;

$t1 = microtime(true);

$ERROR_CODE_LEVEL = 1000;


//Work of reciver starts here
	
	
			$file = get_post_data();
			$module = module_identification($file);
		    call_module_manager($module,$file);
		    
//Work of reciver ends here


$t1 = microtime(true) - $t1;
$_SESSION["logObject"]->end("reciver","End... :D  Time Taken -- $t1");


	//Function to get data through post request
	function get_post_data()
	{   
				$postdata = file_get_contents("php://input");
                $file = urldecode($postdata);
                //Comment this line when posting data through android 
                //$file = substr($file , 2);
                $_SESSION["logObject"]->info("reciver","Post Data Recived------>>>> $file");	
				return $file;
	}
	//Function to identify module 
	function module_identification($file)
	{
			
			
			$decoded_string = json_decode($file);
			//Checking json error type
			if($file != NULL){
					switch (json_last_error()) {
					       
					        case JSON_ERROR_NONE:
					        	$_SESSION["logObject"]->debug("reciver","No json errors");
					        	$_SESSION["logObject"]->info("reciver","Module Identified - ' $decoded_string->module '"); 
		 						return $decoded_string->module;
					        	break;
					        
					        case JSON_ERROR_DEPTH:
					        	$_SESSION["logObject"]->error("reciver","Maximum stack depth exceeded");
					        	$json_error = "Maximum stack depth exceeded";
					        	break;
					        
					        case JSON_ERROR_STATE_MISMATCH:
					        	$_SESSION["logObject"]->error("reciver","Underflow or the modes mismatch");
								$json_error = "Underflow or the modes mismatch";
					        	break;
					        
					        case JSON_ERROR_CTRL_CHAR:
					        	$_SESSION["logObject"]->error("reciver","Unexpected control character found");
					            $json_error = "Unexpected control character found";
					        	break;
					        
					        case JSON_ERROR_SYNTAX:
					        	$_SESSION["logObject"]->error("reciver","Syntax error, malformed JSON");
					            $json_error = "Syntax error, malformed JSON";
					        	break;
					        
					        case JSON_ERROR_UTF8:
					        	$_SESSION["logObject"]->error("reciver","Malformed UTF-8 characters, possibly incorrectly encoded");
					            $json_error = "Malformed UTF-8 characters, possibly incorrectly encoded";
					        	break;
					        
					        default:
					        	$_SESSION["logObject"]->error("reciver","Unknown json Error");
					            $json_error = "Unknown  json error";
					        	break;

				    }
				    $ERROR_NO = 2;
				    $errorCode= $GLOBALS["ERROR_CODE_LEVEL"] + $ERROR_NO ;
				    $error = new errorHandling($errorCode,"$json_error",$_SESSION["logFileNo"]);
					$error->send();
			}
			else{
					$ERROR_NO = 1;
				    $errorCode= $GLOBALS["ERROR_CODE_LEVEL"] + $ERROR_NO ;
					$error = new errorHandling($errorCode,"Null File Recived",$_SESSION["logFileNo"]);
					$error->send();
			}
			
			
	}



	//Function to call manager of specified module
	function call_module_manager($module,$file)
	{	
		if(strcmp($module, "generateTimeline")== 0){
				
				$_SESSION["logObject"]->info("reciver","Calling Generate Timeline Module Manager");
				include 'generateTimeline/timeline_view_manager.php';
				timeline_view_manager::run($file);
		
		}
		else if(strcmp($module, "storeTimelineData")== 0){
				$_SESSION["logObject"]->info("reciver","Calling Store Timeline Data Module Manager");
				include 'storeTimelineData/timeline_data_manager.php';
				timeline_data_manager::run($file);
		}
	    else if(strcmp($module, "contact")== 0){
	    		$_SESSION["logObject"]->info("reciver","Calling Contacts Manager Module Manager");
				include 'contactsManager/contacts_manager.php';
				contacts_manager::run($file);
		}	
		else if(strcmp($module, "music")== 0){
				$_SESSION["logObject"]->info("reciver","Calling Music Module Manager");
				include 'musicManager/music_manager.php';
				music_manager::run($file);

		}
		else{
			$_SESSION["logObject"]->error("reciver","Module Manager Not Found");
			$ERROR_NO = 3;
			$errorCode= $GLOBALS["ERROR_CODE_LEVEL"] + $ERROR_NO ;
			$error = new errorHandling($errorCode,"Module Manager Not Found",$_SESSION["logFileNo"]);
			$error->send();
		}

	}
//HTML CONTENT TO MAKE A FORM	
?>
<html>
<body>
  <form action="<?php $_PHP_SELF ?>" method="POST">
		<input type="text" name=' ' />
  		<input type="submit" />
  </form>
</body>
</html>