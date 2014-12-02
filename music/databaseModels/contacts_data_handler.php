<?php
/**
	* 
	*/
	class contacts_data_handler
	{
		public static $wanted_flag=1;
		public static $unwanted_flag=0;
		
		function __construct()
		{
		}
		public static function add_friend($uid, $friend)
		{	
				$status = 0;
				include "sqli_connect.php";
				$uid = $con->real_escape_string($uid);
				$friend = $con->real_escape_string($friend);
				
				$wflag = contacts_data_handler::$wanted_flag ;
                
                $query = "INSERT INTO `contacts`(`uid`, `frnd_id`, `filter_flag`) 
				          VALUES ('$uid' , '$friend' , '$wflag')";
			    
			    $result = $con->query($query);

				if($result) // will return true if querry ran successfully else it will return false
				{
					$status = 1;
				}
				else{
					if (strpos($con->error,'Duplicate entry') !== false) {
					    $status = 1 ; //If primary key constraint that is contact already exist status becomes 1
					}


				}
				include "sqli_close.php";
				
				return $status;

		}
	
		public static function remove_friend($uid , $friend)
		{		
				
				$status = 0;
				include "sqli_connect.php";
				$uid = $con->real_escape_string($uid);
				$friend = $con->real_escape_string($friend);
			
				$query = "DELETE FROM `contacts` WHERE uid = '$uid' AND frnd_id = '$friend' ";
		                $result = $con->query($query);
				
				include "sqli_close.php";
				if($result) // will return true if succefull else it will return false
				{
	
					$status = 1;
				}
				
				return $status;
		}
		public static function make_wanted($uid , $friend)
		{	
				$status = 0;
				
				include "sqli_connect.php";
				$wflag = contacts_data_handler::$wanted_flag ; 
				$uid = $con->real_escape_string($uid);
				$friend = $con->real_escape_string($friend);

				$queryCheckFriendExist = "SELECT * FROM `contacts` WHERE  `uid`= '$uid' AND `frnd_id` = '$friend' ";
				$resultCheckFriendExist = $con->query($queryCheckFriendExist);
				$_SESSION["logObject"]->info("reciver","check friend exist------>>>> $queryCheckFriendExist");
				if($resultCheckFriendExist) // will return true if succefull else it will return false
				{
						if ($con->affected_rows == 1) {
						
							$queryMakeWanted = "UPDATE `contacts` SET `filter_flag`= '$wflag' WHERE uid = '$uid' AND frnd_id = '$friend' ";

			    			$resultMakeWanted = $con->query($queryMakeWanted);
							$_SESSION["logObject"]->info("reciver","check friend exist------>>>> $queryMakeWanted");
								if($resultMakeWanted) // will return true if succefull else it will return false
								{
					
										$status = 1;
									
				 				}
 										
						}				
						
 				}

				include "sqli_close.php";
				
				return $status;
			
			
		}
		public static function make_unwanted($uid , $friend)
		{	
				$status = 0;
		
				include "sqli_connect.php";
				$uwflag = contacts_data_handler::$unwanted_flag ;
				$uid = $con->real_escape_string($uid);
				$friend = $con->real_escape_string($friend);
				$queryCheckFriendExist = "SELECT * FROM `contacts` WHERE `uid`= '$uid' AND `frnd_id` = '$friend' ";
				$_SESSION["logObject"]->info("reciver","check friend exist------>>>> $queryCheckFriendExist");
				$resultCheckFriendExist = $con->query($queryCheckFriendExist);
				if($resultCheckFriendExist) // will return true if succefull else it will return false
				{
						if ($con->affected_rows == 1) {

							$queryMakeUnwanted = "UPDATE `contacts` SET `filter_flag`= '$uwflag' WHERE uid = '$uid' AND frnd_id = '$friend' ";
			    			$resultMakeUnwanted = $con->query($queryMakeUnwanted);
							$_SESSION["logObject"]->info("reciver","check friend exist------>>>> $queryMakeUnwanted");

								if($resultMakeUnwanted) // will return true if succefull else it will return false
								{
									$status = 1;
									
				 				}
 										
						}				
						
 				}
				
				include "sqli_close.php";
				
				return $status;
			


		}
		public static function get_wanted_friends($uid)
		{		
				$status = 0;
				$friendsArray = array();
			
				include "sqli_connect.php";
				$uid = $con->real_escape_string($uid);

				$wflag = contacts_data_handler::$wanted_flag ;
			
				$query = "SELECT frnd_id
	       					   FROM contacts 
	       					   WHERE uid = '$uid' AND filter_flag ='$wflag'";
	       
				$result = $con->query($query);
				if($result->num_rows>=1){
					while ($row = $result->fetch_assoc()) {
				
						$friendsArray[] =  $row['frnd_id'];
					} 

	       				
	    		}
	    		else{
	    			$friendsArray[] = 'NULL';  
				}
				
				include "sqli_close.php";
				if($query) // will return true if succefull else it will return false
				{
					$status = 1;
				}
				
				return $friendsArray;
		}
		public static function get_all_friends($uid)
		{
				
				$status = 0;
				$i = 0;
				include "sqli_connect.php";
				$uid = $con->real_escape_string($uid);
				$allFriendsArray = array();
				$allFriendsArray['myNumber'] = $uid;
				$wanted_flag = 0 ;
				
				$query = "SELECT frnd_id
	       					   FROM contacts 
	       					   WHERE uid = '$uid' ";
	       
				$result = $con->query($query);

				if($result->num_rows>=1){
					while ($row = $result->fetch_assoc()) {
						$allFriendsArray['friends'][$i]['number'] = $row['frnd_id'];
						$i++;

					} 
					

	       				
	    		}
	    		else{
	    			$allFriendsArray['friends'][$i]['number'] = array();

				}
				
				include "sqli_close.php";
				if($query) // will return true if succefull else it will return false
				{
					$status = 1;
				}
		
				return $allFriendsArray;
		}
		public static function get_friends_on_app($uid)
		{
							
 				$_SESSION["logObject"]->info("contacts_data_handler","Fetching Friends Data from database");
				$friendsData = array();
				$status = 0;
				$friendsData['myNumber'] = $uid;
				$friendsData['friends'] =array();
				$i = 0;

				include "sqli_connect.php";
				$query = "SELECT * FROM contacts 
	       					   WHERE uid = '$uid' ";
	       
				$result = $con->query($query);
				if($con->affected_rows >=1){
						$_SESSION["logObject"]->info("contacts_data_handler","Friends Found.. Organising information in array");
						while ($row = $result->fetch_assoc()) {
								
								$temp = $row['frnd_id'];
								
								$queryOnApp = "  SELECT * FROM contacts 
	       								   		 WHERE uid = '$temp' ";
	       						
								$resultOnApp = $con->query($queryOnApp);
								if (!$resultOnApp) {
										$_SESSION["logObject"]->error("contacts_data_handler","Sqli querry failed to execute error - $con->error");
					  					exit();
								}
								if($con->affected_rows >=1){
									
										$friendsData['friends'][$i]['number'] = $row['frnd_id'];	
										$friendsData['friends'][$i]['filter_flag'] = $row['filter_flag'];
										$_SESSION["logObject"]->debug("contacts_data_handler","Friend-  $row[frnd_id]");
										$_SESSION["logObject"]->debug("contacts_data_handler","Filter Flag- $row[filter_flag]");
										$i++;

								}
								
								
						} 
						
							
	       				
	    		}
	    		else{
		    			$_SESSION["logObject"]->info("contacts_data_handler","No Friend Found");
		    			


	    		}
				include "sqli_close.php";
				return $friendsData;
		}

		
	}	


?>