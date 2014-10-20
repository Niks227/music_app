<?php

/**
* 
*/
class file_parser 
{
	
	function __construct()
	{
		
	}
	public static function get_cmd($file)
	{
		$decoded_string = json_decode($file);
		return $decoded_string->cmd;
	}
	public static function get_myNumber($file)
	{
		$decoded_string = json_decode($file);
		return $decoded_string->myNumber;
	}
	public static function get_fingerprints_array($file)
	{
		$fingerprints_array = array();
		$decoded_string = json_decode($file);
		foreach($decoded_string->fingerprints as $fp){
				$fingerprints_array[] = $fp; 

		}
		
		return $fingerprints_array;
	}
	public static function get_songs_id_array($file)
	{
		$songs_id_array = array();
		$decoded_string = json_decode($file);
		foreach($decoded_string->ids as $sid){
				$songs_id_array[] = $sid; 

		}
		
		return $songs_id_array;
	}
	public static function get_songs_array($file)
	{	
		echo "<Br>Songs fetching started";
		$songs_array = array();
		$row = 0;
		
		$decoded_string = json_decode($file);
		
		foreach($decoded_string->songData as $song){
						
				    		$xml = simplexml_load_string($song->fp);
				    		$songs_array[$row]['xml'] = $song->fp;
							echo "<br> Fingerprinting Algorithm-".$xml->ALGORITHM->NAME ;
				    		$songs_array[$row]['fingerprint_algo'] = $xml->ALGORITHM->NAME;
				    		echo "<br> Algorithm Version-".$xml->ALGORITHM->VERSION ;
				    		$songs_array[$row]['fingerprint_version'] = $xml->ALGORITHM->VERSION;
				    		echo "<br> Fingerprint-".$xml->FP_BLOCKS->FP_BLOCK."<br>" ;
							$songs_array[$row]['fingerprint'] = $xml->FP_BLOCKS->FP_BLOCK;
				    		echo "<br>Filename- $song->fileName";
				    		$songs_array[$row]['filename'] = $song->fileName;
				    		echo "<br>Title- $song->title";
				    		$songs_array[$row]['title'] = $song->title;
				    		echo "<br>Artist- $song->artist";
				    		$songs_array[$row]['artist'] = $song->artist;
				    		echo "<br>Album- $song->album";
				    		$songs_array[$row]['album'] = $song->album;
				    		echo "<br>Duration- $song->duration";
				    		$songs_array[$row]['duration'] = $song->duration;
				    		$row++;
		}
		//var_dump($songs_array);
		return $songs_array;
	}
	public static function get_gracenote_title($data)
	{	
		echo "<br>Gracenote TITLE --->".$data["RESPONSE"]["ALBUM"]["TRACK"]["TITLE"];
		return $data["RESPONSE"]["ALBUM"]["TRACK"]["TITLE"];
	}
	public static function get_gracenote_artist($data)
	{
		$artist = $data["RESPONSE"]["ALBUM"]["ARTIST"];
		if(strcmp("Various Artists", $artist)== 0){

			$artist = $data["RESPONSE"]["ALBUM"]["TRACK"]["ARTIST"];
			
		}
		echo "<br>Gracenote artist --->".$artist;
		return $artist;
	}
	public static function get_gracenote_album($data)
	{
		echo "<br>Gracenote album --->".$data["RESPONSE"]["ALBUM"]["TITLE"];
		return $data["RESPONSE"]["ALBUM"]["TITLE"];
	}
	public static function get_gracenote_genre($data)
	{	
		echo "<br>Gracenote genre --->". $data["RESPONSE"]["ALBUM"]["GENRE"];
		return $data["RESPONSE"]["ALBUM"]["GENRE"];
	}

	public static function get_gracenote_date($data)
	{	
		if(isset($data["RESPONSE"]["ALBUM"]["DATE"])){
				echo "<br>Gracenote DAte --->". $data["RESPONSE"]["ALBUM"]["DATE"];
				return $data["RESPONSE"]["ALBUM"]["DATE"];
	
		}
		else{
				return 0;

		}
	
	}



}