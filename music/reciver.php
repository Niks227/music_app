<?php	
session_start();
include 'generateLog.php';

$log = new generateLog();

$_SESSION["logObject"] = $log;
//Work of reciver starts here
	
	$file = get_post_data();
	$module = module_identification($file);
    call_module_manager($module,$file);

//Work of reciver ends here


	//Function to get data through post request
	function get_post_data()
	{   
				$postdata = file_get_contents("php://input");
                $file = urldecode($postdata);
                //Comment this line when posting data through android 
                $file = substr($file , 2);
                $_SESSION["logObject"]->debug("reciver","Post Data Recived");	
				return $file;
	}
	//Function to identify module 
	function module_identification($file)
	{
			//json parsing
		
			$decoded_string = json_decode($file);
			//Checking json error type
			switch (json_last_error()) {
			       
			        case JSON_ERROR_NONE:
			        	$_SESSION["logObject"]->debug("reciver","No json errors");
			        	
			       //   echo ' - No json errors';
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
			            $json_error = "Unknown error";
			        	break;
		    }
			//Check file type is json or not
 			if((json_last_error() == JSON_ERROR_NONE)==1){
 				
				$_SESSION["logObject"]->debug("reciver","Module Identified - ' $decoded_string->module '"); 
 				return $decoded_string->module;
 			}
 			else{
 				
 				$arr = array ('ERROR'=>"$json_error");
		    	echo json_encode($arr);
			    echo $jsonstring;
				
 			}
			
	  		

	
	}
	//Function to call manager of specified module
	function call_module_manager($module,$file)
	{	
		if(strcmp($module, "generate_timeline")== 0){
				$_SESSION["logObject"]->debug("reciver","Calling Generate Timeline Module Manager");
				//echo "<h1>MODULE - <u>Generate Timeline</u> </h1><br>";
				include 'generateTimeline/timeline_view_manager.php';
				timeline_view_manager::run($file);
		
		}
		else if(strcmp($module, "store_timeline_data")== 0){
				$_SESSION["logObject"]->debug("reciver","Calling Store Timeline Data Module Manager");
				//echo "<h1>MODULE - <u>Store Timeline Data </u> </h1><br>";
				include 'storeTimelineData/timeline_data_manager.php';
				timeline_data_manager::run($file);
		}
	    else if(strcmp($module, "contact")== 0){
	    		$_SESSION["logObject"]->debug("reciver","Calling Contacts Manager Module Manager");
				//echo "<h1>MODULE - <u>Contacts Manager </u> </h1><br>";
				include 'contactsManager/contacts_manager.php';
				contacts_manager::run($file);
		}	
		else if(strcmp($module, "music")== 0){
				$_SESSION["logObject"]->debug("reciver","Calling Music Module Manager");
				//echo "<h1>MODULE - <u>Music Module</u> </h1> <br>";
				include 'musicManager/music_manager.php';
				music_manager::run($file);

		}
		else{
			$_SESSION["logObject"]->error("reciver","Module Manager Not Found");
			$arr = array ('ERROR'=>"Module $module manager not found");
	    	echo json_encode($arr);
		
			//exit();

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



