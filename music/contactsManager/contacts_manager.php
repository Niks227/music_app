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
			
			
			$i= 0;
			$contacts_status = array();
			$decoded_string = json_decode($file);
			$uid  =  file_parser::get_user_no($file);
			//echo "<h3> $uid </h3><br>";
			foreach ($decoded_string->contactList as $item) {
				  
				  $friend = $item->number;
				  $contacts_status['contactList'][$i]['number'] = $friend;
                                  $contacts_status['contactList'][$i]['action'] = $item->action;
				  switch ($item->action) {
						case 'add':
							//echo "User -$uid Contacts added";
							$contacts_status['contactList'][$i]['status']= contacts_data_handler::add_friend($uid, $friend);
							
							break;
						case 'make_wanted':
							//echo "Marking contact as wanted<br>";
							$contacts_status['contactList'][$i]['status']=  contacts_data_handler::make_wanted($uid , $friend);
							break;
						case 'make_unwanted':
							//echo "Marking contact as unwanted<br>";
							$contacts_status['contactList'][$i]['status']=  contacts_data_handler::make_unwanted($uid , $friend);
							break;
						case 'remove':
							//echo "Removing Friends<br>";
							$contacts_status['contactList'][$i]['status']=  contacts_data_handler::remove_friend($uid , $friend);
							break;
						
						default:
						        $contacts_status['contactList'][$i]['status']= 0;
					                break;
			            }
			          
			            $i++;
				
			}
			
	    	echo json_encode($contacts_status);
			
			

	}
}


?>