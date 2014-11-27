<?php
/**
* 
*/
class music_data_handler
{
	
	function __construct()
	{
	}
	public static function get_music_details($sid)
	{
		//	echo "<br>Getting Music details from sid- ". $sid;
		$musicInfo = array();
		
		include "sqli_connect.php";
		
				$query = " SELECT * FROM `music` WHERE `song_id` = $sid ";
				$result = $con->query($query);
				if (!$result) {
				   printf("%s\n", $con->error);
				   exit();
				}
				
				while ($row = $result->fetch_assoc()) {
						$musicInfo['title'] = $row['title'];
						$musicInfo['album'] = $row['album'];
						$musicInfo['artist'] = $row['artist'];
						$musicInfo['genre'] = $row['genre'];
						$musicInfo['date'] = $row['date'];
						$musicInfo['duration'] = $row['duration'];
						$musicInfo['albumArtLink'] = $row['album_art_link'];
				//		$musicInfo['songLink'] = $row['songLink'];	
						
							
				} 
					
	       				
	    include "sqli_close.php";
	    return $musicInfo;
	}
	public static function store_music_data($song_id , $gracenote_title , $gracenote_album , $gracenote_artist ,$gracenote_genre , $duration ,  $gracenote_date , $art_url)
	{		
			$song_id = addslashes($song_id);
			$gracenote_title = addslashes($gracenote_title);
			$gracenote_genre = addslashes($gracenote_genre);
			$gracenote_date = addslashes($gracenote_date);
			$gracenote_artist = addslashes($gracenote_artist);
			$gracenote_album = addslashes($gracenote_album);
			$duration = addslashes($duration);
			$art_url = addslashes($art_url);



			include "sqli_connect.php";
				
						$query = "INSERT INTO `music`(`song_id`, `title`, `artist`, `album`, `genre`, `date`, `duration`, `album_art_link`)
						 VALUES ('$song_id','$gracenote_title','$gracenote_artist','$gracenote_album','$gracenote_genre','$gracenote_date','$duration','$art_url')";
				
						//echo $query;
						$result = $con->query($query);

			
				
			include "sqli_close.php";
	}
}

?>