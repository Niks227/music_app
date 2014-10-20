<?php
/**
* 
*/
class music_data_handler
{
	
	function __construct()
	{
	}
	public static function get_details($sid)
	{
			echo "<br>Getting Music details from sid- ". $sid;
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