<?php

/**
* 
*/
class file_parser 
{
	
	function __construct()
	{
		
	}
	
	public static function get_user_no($file)
	{	
		echo "Getting user no... <br>";
                $file = stripslashes($file);
		$decoded_string = json_decode($file);
  		return $decoded_string->details->user_no;
  		//checkk userno validity
		
	}
	
	public static function get_fingerprints($file)
	{			
				echo "Getting fingerprints... <br>";
				$fingerprints_array = array();
                                $file = stripslashes($file);
  			 	$decoded_string     = json_decode($file);

  			 	foreach($decoded_string->details->fingerprints as $fps){
				     $fingerprints_array[] = $fps;

				} 
				return $fingerprints_array;
				//check sound cloud id 
	}
	
	public static function get_flags($file)
	{	
				echo "Getting flags... <br>";
				$flags_array    = array();
                                $file = stripslashes($file);
  			 	$decoded_string = json_decode($file);

  			 	foreach($decoded_string->details->flags as $f){
				     $flags_array[] = $f;

				} 
				return $flags_array;
	}

	public function get_songs($sids_arary , $fingerprints_array, $flags_array )
	{
				$songs_array = array();
				include '../music/musicManager/song.php';
				echo "<br><br><br>Making of Songs objects started...";
				foreach (array_keys($sids_arary) as $key) {
    				if(strcmp($sids_arary[$key], "#NF")!= 0){
						echo "<br>Song Made";
						$songs_array[] =  new     song($sids_arary[$key],$fingerprints_array[$key],$flags_array[$key]);
					}
					echo  "<br>Song Id - "    . $sids_arary[$key];
					echo  "<br>Fingerprint -" . $fingerprints_array[$key];
					echo  "<br>Flag -"        . $flags_array[$key]. "<br>";
					
				}	
				return $songs_array;		
		

	}


}