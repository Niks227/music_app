<?php

	/**
	* 
	*/
	
	class timeline_data_handler{
		
		function __construct()
		{
		}
		public function check_existence($uid){
			include "sqli_connect.php";
			$query = " SELECT * FROM timeline_data WHERE uid = '$uid' limit 1  "; 
						

			$result = $con->query($query);
			if (!$result) {
			   printf("%s\n", $con->error);
			   exit();
			}
			if($result->num_rows==1){
				return 1;	//1 stands fo user exists
					
			}
			else 
				return 0;	//0 stands for user does not exists

			include "sqli_close.php";

		}
		public static function get_friends_sids($friends)
		{
			
			include "sqli_connect.php";
			$sids='';
			$query = " SELECT sids FROM timeline_data WHERE uid IN ($friends) "; 
			
						

			$result = $con->query($query);
			if (!$result) {
			   printf("%s\n", $con->error);
			   exit();
			}
			while ($row = $result->fetch_assoc()) {
       			 $sids = $sids . $row['sids'];
    		}

			include "sqli_close.php";
			return $sids;
			
		}
		public static function get_friends_data($friends)
		{
			
			include "sqli_connect.php";

			
			
			$sidsScores =array();
			$sidsScores['sids']   = '';
			$sidsScores['scores'] = '';
			$query = " SELECT * FROM timeline_data WHERE uid IN ($friends) "; 
			$_SESSION["logObject"]->debug("timeline_view_manager","Sids String-- $query END");			

			$result = $con->query($query);
			if ($result) {
				while ($row = $result->fetch_assoc()) {
					$sidsScores['sids']   = $sidsScores['sids'].$row['sids'];
					$sidsScores['scores'] = $sidsScores['scores'].$row['scores']; 
    			}	
			
			}
			
    		
			include "sqli_close.php";
			return $sidsScores;
			
		}
		public static function get_friends_scores($friends)
		{	
			include "sqli_connect.php";
			$scores='';
			$query = " SELECT scores FROM timeline_data WHERE uid IN ($friends) "; 
						

			$result = $con->query($query);
			if (!$result) {
			   printf("%s\n", $con->error);
			   exit();
			}
			while ($row = $result->fetch_assoc()) {
       			 $scores = $scores . $row['scores'];
    		}

			include "sqli_close.php";
			return $scores;

		}
		public static function add_user($uid , $songs_ids , $scores){
				include "sqli_connect.php";

				$uid       = $con->real_escape_string($uid);
				$songs_ids = $con->real_escape_string($songs_ids);
				$scores    = $con->real_escape_string($scores);
				$_SESSION["logObject"]->end("reciver","$uid $songs_ids $scores");
				
				$result = $con->query("INSERT INTO timeline_data (uid, sids, scores)
												VALUES ('$uid' , '$songs_ids' , '$scores')");
				if($result) // will return true if querry ran successfully else it will return false
				{//echo "chall gayyaaa!!!";
					$status = 1;
				}
				else{
					$status = 0;
				}
				include "sqli_close.php";
 			//	echo "New user added<br>";
 				return $status;
		}
		public static function insert_updated_data($uid , $songs_ids , $scores){
				include "sqli_connect.php";
				
				$uid       = $con->real_escape_string($uid);
				$songs_ids = $con->real_escape_string($songs_ids);
				$scores    = $con->real_escape_string($scores);
				
				
			
				$result = $con->query("UPDATE timeline_data SET sids='$songs_ids',scores ='$scores' where uid = '$uid'");
 				if($result) // will return true if querry ran successfully else it will return false
				{
					$status = 1;
				}
				else{
					$status = 0;
				}
 				
 				include "sqli_close.php";
 		//		echo "<br>User data updated";
 				return $status;
		}
		public static function delete_user($uid){


		}
		public static function get_sids($uid){
			include "sqli_connect.php";
			$query = " SELECT * FROM timeline_data WHERE uid = '$uid' limit 1  "; 
						

			$result = $con->query($query);
			if (!$result) {
			   printf("%s\n", $con->error);
			   exit();
			}
			while ($row = $result->fetch_assoc()) {
       			 $sids = $row['sids'];
    		}

			include "sqli_close.php";
			return $sids;


		}
		public static function get_scores($uid){
			include "sqli_connect.php";
			$query = " SELECT * FROM timeline_data WHERE uid = '$uid' limit 1  "; 
						

			$result = $con->query($query);
			if (!$result) {
			   printf("%s\n", $con->error);
			   exit();
			}
			while ($row = $result->fetch_assoc()) {
       			 $scores = $row['scores'];
    		}

			include "sqli_close.php";
			return $scores;

		}

	}
	

