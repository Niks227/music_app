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
			$MY_NUMBER         =  file_parser::get_user_no($file);
			$_SESSION["logObject"]->debug("contacts_manager","User's Number is -- $MY_NUMBER");
		

			if(isset($decoded_string->cmd)){
				$_SESSION["logObject"]->info("contacts_manager","External command found");			
				$_SESSION["logObject"]->info("contacts_manager","Sending Friends Data");
				$cmd = $decoded_string->cmd;			
				$friendsData = contacts_manager::processExternalCmd($MY_NUMBER, $cmd);	
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
	public static function processExternalCmd($myNumber , $cmd)
	{
			if(strcmp($cmd, "sendFriendsOnApp") == 0)
				return contacts_data_handler::get_friends_on_app($myNumber);
			elseif (strcmp($cmd, "sendAllFriends") == 0) 
				return contacts_data_handler::get_all_friends($myNumber);

	}
	public static function processInternalCmd($decoded_string)
	{
		$i                   = 0;
		$ADD_COUNT           = 0;
		$REMOVE_COUNT        = 0;
		$MAKE_WANTED_COUNT   = 0;
		$MAKE_UNWANTED_COUNT = 0;
		$MY_NUMBER = $decoded_string->myNumber;

		foreach ($decoded_string->contactsList as $item) {
				
					 		$_SESSION["logObject"]->info("contacts_manager","New Contact");
							$FRIEND = $item->number;
							$ACTION = $item->action;
					 		$_SESSION["logObject"]->debug("contacts_manager","New Friend -- $FRIEND");

							$contacts_status['contactList'][$i]['number'] = $FRIEND;
			                 

			                $contacts_status['contactList'][$i]['action'] = $ACTION;
			 		 		$_SESSION["logObject"]->debug("contacts_manager","Friend action -- $ACTION ");
			 
							  switch ($ACTION) {
									case 'add':
										$ADD_COUNT++;
										$contacts_status['contactList'][$i]['status']= contacts_data_handler::add_friend($MY_NUMBER, $FRIEND);
										$_SESSION["logObject"]->info("contacts_manager"," $FRIEND Friend Added ");
										break;
									case 'makeWanted':
										$MAKE_WANTED_COUNT++;
										$contacts_status['contactList'][$i]['status']=  contacts_data_handler::make_wanted($MY_NUMBER , $FRIEND);
										$_SESSION["logObject"]->debug("contacts_manager"," $FRIEND Friend  Marked as wanted");
										break;
									case 'makeUnwanted':
										$MAKE_UNWANTED_COUNT++;
										$_SESSION["logObject"]->debug("contacts_manager","$FRIEND Friend  Marked as unwanted");
										$contacts_status['contactList'][$i]['status']=  contacts_data_handler::make_unwanted($MY_NUMBER , $FRIEND);
										break;
									case 'remove':
										$REMOVE_COUNT++;
										$_SESSION["logObject"]->debug("contacts_manager","Friend  Removed");
										$contacts_status['contactList'][$i]['status']=  contacts_data_handler::remove_friend($MY_NUMBER , $FRIEND);
										break;
									
									default:
									    $_SESSION["logObject"]->error("contacts_manager","Command Not Recognised");
									    $contacts_status['contactList'][$i]['status']= 0;
								        break;
						       }
						          
						            $i++;
							
		}
		$_SESSION["logObject"]->debug("contacts_manager","CONTACTS added-- $ADD_COUNT");
		$_SESSION["logObject"]->debug("contacts_manager","CONTACTS removed-- $REMOVE_COUNT");
		$_SESSION["logObject"]->debug("contacts_manager","CONTACTS made wanted-- $MAKE_WANTED_COUNT");
		$_SESSION["logObject"]->debug("contacts_manager","CONTACTS made unwanted-- $MAKE_UNWANTED_COUNT");
		return $contacts_status;
		
	}
}


?>