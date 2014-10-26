<?php

		include '../music/databaseModels/contacts_data_handler.php';
		include 'file_parser.php';

/**
* 
*/
class contacts_manager
{
 	
	function __construct()
	{
	}
	public static function run ($file)
	{	
			
			$_SESSION["logObject"]->info("contacts_manager","Contacts Manager started its work");
			
			$contacts_status   = array();
			$decoded_string    = json_decode($file);
			$uid               =  file_parser::get_user_no($file);
			$_SESSION["logObject"]->debug("contacts_manager","User's Number is -- $uid");
		

			if(isset($decoded_string->cmd) && strcmp($decoded_string->cmd, "sendFriendsData")==0){
				$_SESSION["logObject"]->info("contacts_manager","External command found");			
				$_SESSION["logObject"]->info("contacts_manager","Sending Friends Data");			
				$friendsData = contacts_manager::processExternalCmd($decoded_string->myNumber);	
				$_SESSION["logObject"]->info("contacts_manager","Converting arrray to json and sending information..");
				$send = json_encode($friendsData);
				echo "$send";
				$_SESSION["logObject"]->info("contacts_manager","$send");

			}
			else{
					$_SESSION["logObject"]->info("contacts_manager","Iternal command found");
					$_SESSION["logObject"]->info("contacts_manager","Proceesing Contacts List");			
					$contacts_status = contacts_manager::processInternalCmd($decoded_string);
					echo json_encode($contacts_status);		
			}
		
			

	}
	public static function processExternalCmd($myNumber)
	{
			return contacts_data_handler::get_friends_data($myNumber);

	}
	public static function processInternalCmd($decoded_string)
	{
		$i                 = 0;
		$addCount          = 0;
		$removeCount       = 0;
		$makeWantedCount   = 0;
		$makeUnwantedCount = 0;
		$uid = $decoded_string->myNumber;

		foreach ($decoded_string->contactsList as $item) {
				
					 		  $_SESSION["logObject"]->info("contacts_manager","New Contact");
				
							  $friend = $item->number;
					 		  $_SESSION["logObject"]->debug("contacts_manager","New Friend -- $friend");

							  $contacts_status['contactList'][$i]['number'] = $friend;
			                 

			                  $contacts_status['contactList'][$i]['action'] = $item->action;
			 		 		  $_SESSION["logObject"]->debug("contacts_manager","Friend action -- $item->action ");
			 
							  switch ($item->action) {
									case 'add':
										$addCount++;
										$_SESSION["logObject"]->info("contacts_manager","Friend Added ");						    
										$contacts_status['contactList'][$i]['status']= contacts_data_handler::add_friend($uid, $friend);
										
										break;
									case 'makeWanted':
										$makeWantedCount++;
										$_SESSION["logObject"]->debug("contacts_manager","Friend  Marked as wanted");
										$contacts_status['contactList'][$i]['status']=  contacts_data_handler::make_wanted($uid , $friend);
										break;
									case 'makeUnwanted':
										$makeUnwantedCount++;
										$_SESSION["logObject"]->debug("contacts_manager","Friend  Marked as unwanted");
										$contacts_status['contactList'][$i]['status']=  contacts_data_handler::make_unwanted($uid , $friend);
										break;
									case 'remove':
										$removeCount++;
										$_SESSION["logObject"]->debug("contacts_manager","Friend  Removed");
										$contacts_status['contactList'][$i]['status']=  contacts_data_handler::remove_friend($uid , $friend);
										break;
									
									default:
									    $_SESSION["logObject"]->error("contacts_manager","Command Not Recognised");
									    $contacts_status['contactList'][$i]['status']= 0;
								        break;
						       }
						          
						            $i++;
							
		}
		return $contacts_status;
		
	}
}


?>