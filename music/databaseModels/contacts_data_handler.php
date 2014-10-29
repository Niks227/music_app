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
				$wflag = contacts_data_handler::$wanted_flag ;
                
                $query = "INSERT INTO `contacts`(`uid`, `frnd_id`, `filter_flag`) 
				          VALUES ('$uid' , '$friend' , '$wflag')";
			    
			    $result = $con->query($query);

				if($result) // will return true if querry ran successfully else it will return false
				{//echo "chall gayyaaa!!!";
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
				//echo "Removing friends.. gud bye.. :(";
				$status = 0;
				include "sqli_connect.php";
				
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
		//		echo "Marking friend  as wanted";
				include "sqli_connect.php";
				$wflag = contacts_data_handler::$wanted_flag ; 
			//	$uid = mysql_real_escape_string($uid);
			//	$friend = mysql_real_escape_string($friend); 
				$queryCheckFriendExist = "SELECT * FROM `contacts` WHERE  `uid`= '$uid' AND `frnd_id` = '$friend' ";
				$resultCheckFriendExist = $con->query($queryCheckFriendExist);
$_SESSION["logObject"]->info("reciver","check friend exist------>>>> $queryCheckFriendExist");
				if($resultCheckFriendExist) // will return true if succefull else it will return false
				{
						if ($con->affected_rows == 1) {
						//	echo "Friend Found";
							$queryMakeWanted = "UPDATE `contacts` SET `filter_flag`= '$wflag' WHERE uid = '$uid' AND frnd_id = '$friend' ";

			    			$resultMakeWanted = $con->query($queryMakeWanted);
$_SESSION["logObject"]->info("reciver","check friend exist------>>>> $queryMakeWanted");
								if($resultMakeWanted) // will return true if succefull else it will return false
								{
						//				echo "UPDATED successfully";
						//				echo "chal gaya..  3";
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
		//		echo "Marking friend  as unwanted";
				include "sqli_connect.php";
				$uwflag = contacts_data_handler::$unwanted_flag ;
			//	$uid = mysql_real_escape_string($uid);
			//	$friend = mysql_real_escape_string($friend); 
				$queryCheckFriendExist = "SELECT * FROM `contacts` WHERE `uid`= '$uid' AND `frnd_id` = '$friend' ";
$_SESSION["logObject"]->info("reciver","check friend exist------>>>> $queryCheckFriendExist");
				$resultCheckFriendExist = $con->query($queryCheckFriendExist);
				if($resultCheckFriendExist) // will return true if succefull else it will return false
				{
						if ($con->affected_rows == 1) {
//							echo "Friend Found";
							$queryMakeUnwanted = "UPDATE `contacts` SET `filter_flag`= '$uwflag' WHERE uid = '$uid' AND frnd_id = '$friend' ";
			    			$resultMakeUnwanted = $con->query($queryMakeUnwanted);
$_SESSION["logObject"]->info("reciver","check friend exist------>>>> $queryMakeUnwanted");

								if($resultMakeUnwanted) // will return true if succefull else it will return false
								{
//										echo "UPDATED successfully";
//										echo "chal gaya..  4";
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
				//echo "<br>Getting Wanted Friends<br>";
				include "sqli_connect.php";
				$wflag = contacts_data_handler::$wanted_flag ;
				$friends = '';
				$query = "SELECT frnd_id
	       					   FROM contacts 
	       					   WHERE uid = '$uid' AND filter_flag ='$wflag'";
	       
				$result = $con->query($query);
				if (!$result) {
				   printf("%s\n", $con->error);
				   exit();
				}
				if($result->num_rows>=1){
					while ($row = $result->fetch_assoc()) {
						$friends = $friends . $row['frnd_id']. ",";	
					} 
					$friends = rtrim($friends, ",");

	       				
	    		}
	    		else{
	    			$friends = "NF"; //#NF - stands for not found
				}
				echo $friends."<br>";
				include "sqli_close.php";
				if($query) // will return true if succefull else it will return false
				{
					$status = 1;
				}
				
				//return $status;
			
				return $friends;
		}
		public static function get_all_friends($uid)
		{
				//echo "<br>Getting All Friends<br>";
				$status = 0;
				include "sqli_connect.php";
				$wanted_flag = 0 ;
				$friends = '';
				$query = "SELECT frnd_id
	       					   FROM contacts 
	       					   WHERE uid = '$uid' ";
	       
				$result = $con->query($query);
				if (!$result) {
				   printf("%s\n", $con->error);
				   exit();
				}
				if($result->num_rows>=1){
					while ($row = $result->fetch_assoc()) {
						$friends = $friends . $row['frnd_id']. ",";	
					} 
					$friends = rtrim($friends, ",");

	       				
	    		}
	    		else{
	    			$friends = "NF"; //#NF - stands for not found
				}
				echo $friends."<br>";
				include "sqli_close.php";
			if($query) // will return true if succefull else it will return false
				{
					$status = 1;
				}
				
				//return $status;
				return $friends;
		}
		public static function get_friends_data($uid)
		{
 $count = 0;				
 $_SESSION["logObject"]->info("contacts_data_handler","Fetching Friends Data from database");
				$friendsData = array();
				$status = 0;
				$friendsData['myNumber'] = $uid;
				$i = 0;

				include "sqli_connect.php";
				$query = "SELECT * FROM contacts 
	       					   WHERE uid = '$uid' ";
	       
				$result = $con->query($query);
				if (!$result) {
					$_SESSION["logObject"]->error("contacts_data_handler","Sqli querry failed to execute error - $con->error");
				   
				   	exit();
				}
				if($result->num_rows>=1){
					$_SESSION["logObject"]->info("contacts_data_handler","Friends Found.. Organising information in array");
					while ($row = $result->fetch_assoc()) {
$count++;
						$friendsData['friends'][$i]['number'] = $row['frnd_id'];	
						$friendsData['friends'][$i]['filter_flag'] = $row['filter_flag'];
						$_SESSION["logObject"]->debug("contacts_data_handler","Friend-  $row[frnd_id]");
						$_SESSION["logObject"]->debug("contacts_data_handler","Filter Flag- $row[filter_flag]");
						$i++;
					} 
$_SESSION["logObject"]->debug("contacts_data_handler","Count $count");
						
	       				
	    		}
	    		else{
	    			$_SESSION["logObject"]->info("contacts_data_handler","No Friend Found");
	    			$friendsData['friends'] =array();


	    		}
				include "sqli_close.php";
				return $friendsData;
		}

		
	}	


?>