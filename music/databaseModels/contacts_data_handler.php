<?php
/**
	* 
	*/
	class contacts_data_handler
	{
		public static $wanted_flag=0;
		public static $unwanted_flag=1;
		
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
			
				
				include "sqli_close.php";
				if($query) // will return true if succefull else it will return false
				{
					$status = 1;
				}
				
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
				if($query) // will return true if succefull else it will return false
				{
					$status = 1;
				}
				
				return $status;
		}
		public static function make_wanted($uid , $friend)
		{	
				$status = 0;
				echo "Marking friend  as wanted";
				include "sqli_connect.php";
				$wflag = contacts_data_handler::$wanted_flag ; 
				$query = "UPDATE `contacts` SET `filter_flag`= '$wflag' WHERE uid = '$uid' AND frnd_id = '$friend' ";
	                        $result = $con->query($query);
				
				include "sqli_close.php";
				if($query) // will return true if succefull else it will return false
				{
					$status = 1;
				}
				
				return $status;
			
		}
		public static function make_unwanted($uid , $friend)
		{	
				$status = 0;
				echo "Marking friend  as unwanted";
				include "sqli_connect.php";
				$uwflag = contacts_data_handler::$unwanted_flag ; 
				$query = "UPDATE `contacts` SET `filter_flag`= '$uwflag' WHERE uid = '$uid' AND frnd_id = '$friend' ";
			        $result = $con->query($query);
				
				include "sqli_close.php";
				if($query) // will return true if succefull else it will return false
				{
					$status = 1;
				}
				
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
		public static function get_all_friendss($uid)
		{
				//echo "<br>Getting Wanted Friends<br>";
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
		
	}	


?>