<?php
/**
* 
*/
class user_songs_handler
{
	
	function __construct()
	{
	}

	
	public static function add_user_song($myNumber , $song_id , $fp)
	{
		$myNumber = addslashes($myNumber);
		$song_id = addslashes($song_id);
		$fp = addslashes($fp);
		include "sqli_connect.php";
		
		$query = "INSERT INTO `user_songs`(`user_id`, `song_id`, `fingerprint`) VALUES ('$myNumber','$song_id','$fp')";		
						
		$result = $con->query($query);


		include "sqli_close.php";
		if (!$result) {
			    return "failed";
		}
		else{
				return "done";
		}


	}
	public static function delete_user_song($myNumber , $song_id)
	{
		$myNumber = addslashes($myNumber);
		$song_id = addslashes($song_id);
		$failed = 0;
		
                $done_one_deleted = 1;
                $done_zero_deleted = 2;
		
		include "sqli_connect.php";
		
		$query = " DELETE FROM `user_songs` WHERE user_id = '$myNumber' AND song_id = $song_id ";
             
		$result = $con->query($query);
		
		
               if (!$result) {
                               include "sqli_close.php";
		               return $failed;
               }
               else{
	            if($con->affected_rows  == 0){
		         	include "sqli_close.php";
	                        return $done_zero_deleted;

		    }
	            else if($con->affected_rows  == 1){
                                 include "sqli_close.php";
			         return $done_one_deleted;
		    }



                }
			


			
	}
	public static function get_sid( $user_no  , $fingerprint)
	{	
			include "sqli_connect.php";
			$query = "SELECT song_id
       					   FROM user_songs 
       					   WHERE user_id = '$user_no' && fingerprint = '$fingerprint'";
			$result = $con->query($query);
			if (!$result) {
			   printf("%s\n", $con->error);
			   exit();
			}
			if($result->num_rows==1){
				while ($row = $result->fetch_assoc()) {
					$sid = $row['song_id'];	
				} 
       				
    		}
    		else{
    			$sid = "#NF"; //#NF - stands for not found
			}
//			echo $sid."<br>";
			include "sqli_close.php";
		
			return $sid;
	}
}