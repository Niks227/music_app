<?php

class music_manager
{
	
	function __construct()
	{	
            
	}
	public static function run($file)
	{	
		$_SESSION["logObject"]->info("music_manager","Music Manager Started its Work");
		include 'gracenote.php';
		include 'file_parser.php';
		include 'album_art_finder.php';
		include 'user_songs_controller.php';
		include 'unidentified_fps_controller.php';
		include '../music/databaseModels/music_data_handler.php';
		include '../music/databaseModels/user_songs_handler.php';
		include '../music/databaseModels/unidentified_fps_handler.php';

		$_SESSION["logObject"]->debug("music_manager","Fetching User Number");
		$myNumber = file_parser::get_myNumber($file);
		$_SESSION["logObject"]->debug("music_manager","Fetching Command");
		$cmd = file_parser::get_cmd($file);
		
		switch ($cmd) {
			case "add":
			//	echo "<br>Started adding songs";
			$_SESSION["logObject"]->debug("music_manager","Command - ADD ");
				music_manager::add_songs($file,$myNumber);	

				break;
			case "delete":
			//	echo "Started deleting songs";
			$_SESSION["logObject"]->debug("music_manager","Command - Delete ");
				music_manager::delete_songs($file,$myNumber);

				break;
			case "getSongsList":
			//	echo "<br>Getting songs";
			$_SESSION["logObject"]->debug("music_manager","Command - getSongList ");
				music_manager::get_songs_list($myNumber);

				break;

			case "getSongStatus":
			//	echo "<br>Getting Song Status";
			$_SESSION["logObject"]->debug("music_manager","Command - getSongStatus ");
				music_manager::get_song_status($file,$myNumber);
				
				break;
			default:
			$_SESSION["logObject"]->error("music_manager","Command - Not Recognized ");
			    echo "Cmd not recognized";
			
				break;
		}


		
			
			
	}
	public static function get_song_status($file,$myNumber)
	{
			$found    = 1;
			$notFound = 0;
			$k = 0;		
			$response_array['songsStatus']= array();
			$fingerprints_array = file_parser::get_fingerprints_array($file);
			
			foreach ($fingerprints_array as $fp) {
			
				$result1 = user_songs_controller::check_db($fp , $myNumber);
				if($result1['status']==$found){
						$response_array['songsStatus'][$k]['fp'] = $fp;
						$response_array['songsStatus'][$k]['id'] = $result1['sid'];
						$k++;


				}
    				else{


						$result2 = unidentified_fps_controller::check_unidentified_fps($fp , $myNumber);
						if($result2['status']==$found){
							$response_array['songsStatus'][$k]['fp'] = $fp;
							$response_array['songsStatus'][$k]['id'] = $result2['sid'];
							$k++;
                                                         
						}
   						else{
						//	$response_array['songsStatus'] = array();
						}

				}

				
		    	
			}

			echo json_encode($response_array);


	}
	
	public static function get_songs_list($myNumber){
	
			$music_list_response = user_songs_controller::get_music_info($myNumber);
			//Sending response
			echo json_encode($music_list_response);
	
	}
	
	public static function delete_songs($file,$myNumber)
	{
			$response_array = array();
			$i = 0;
			$songs_id_array = file_parser::get_songs_id_array($file);
			foreach ($songs_id_array as $song_id) {
				$status =user_songs_handler::delete_user_song($myNumber , $song_id );
				$response_array['songData'][$i]['id']   = $song_id;
		    	$response_array['songData'][$i]['status'] = $status;
		    	$i++;

			}
			echo json_encode($response_array);
			
	}
	public static function freeUserBrowser($response)
	{
	        // let's free the user, but continue running the
	        // script in the background
			
			ob_flush();
	 
	        ignore_user_abort(true);
	        header("Connection: close");

	        header("Content-Length:40");
	        
	        echo $response;
	        flush();

	}
	public static function add_songs($file,$myNumber)
	{
			
			
			
				$response = "{
								\"status\":\"1\"
							 } 
						";
				$old_algo_status = 0;
			$_SESSION["logObject"]->info("music_manager","Freeing User's Browser ");
			music_manager::freeUserBrowser($response);
			$_SESSION["logObject"]->debug("music_manager","From the received json file organise all songs information in an array ");
			$songs_array = file_parser::get_songs_array($file);
			$_SESSION["logObject"]->debug("music_manager","For each Song repeat identification algorithms");
			foreach ($songs_array as $song) {
				
				$fp_xml       =   $song['xml'];
				$fp_algo      =   $song['fingerprint_algo'];
				$fp_ver       =   $song['fingerprint_version'];
				$fp           =   $song['fingerprint'];
				$duration     =   $song['duration']; 
	            $badTitle     =   $song['title'];	
	            $badArtist    =   $song['artist'];
	            $badAlbum     =   $song['album'];
	            $badDuration  =   $song['duration'];	
				
				$_SESSION["logObject"]->debug("music_manager","New Song");
				$_SESSION["logObject"]->debug("music_manager","Using Grace-note");
				$data = gracenote::gracenote_details($fp_algo , $fp_ver , $fp);
				

                               
				if(strcmp($data["RESPONSE"]["@attributes"]["STATUS"],"OK")==0){
					
					$_SESSION["logObject"]->debug("music_manager","Grace-note Worked :) ");
					
					
					$gracenote_title = file_parser::get_gracenote_title($data);
					$gracenote_album = file_parser::get_gracenote_album($data);
					$gracenote_artist = file_parser::get_gracenote_artist($data);
					$gracenote_genre = file_parser::get_gracenote_genre($data);
					$gracenote_date = file_parser::get_gracenote_date($data);
					$_SESSION["logObject"]->debug("music_manager","Gracenote Title - $gracenote_title");
					$_SESSION["logObject"]->debug("music_manager","Gracenote Album - $gracenote_album");
					$_SESSION["logObject"]->debug("music_manager","Gracenote Artist - $gracenote_artist");
					$_SESSION["logObject"]->debug("music_manager","Gracenote genre - $gracenote_genre");
					$_SESSION["logObject"]->debug("music_manager","Gracenote Date - $gracenote_date");	
					
					$_SESSION["logObject"]->debug("music_manager","Proceeding to further Steps");
					music_manager::proceed($fp_xml , $myNumber,$gracenote_title , $gracenote_album , $gracenote_artist ,$gracenote_genre , $gracenote_date , $duration);

				}
				else{
					$_SESSION["logObject"]->debug("music_manager","Grace-note could not work.. :( ");
					$_SESSION["logObject"]->debug("music_manager","Will Proceed to old algorithm");
					$old_algo_status = old_algo($badTitle , $badArtist , $badAlbum , $badDuration);
					if($old_algo_status == 1){
						music_manager::proceed($fp_xml , $myNumber,$gracenote_title , $gracenote_album , $gracenote_artist ,$gracenote_genre , $gracenote_date , $duration);

					}
					else{
					//	echo "store incomplete in table";
						$_SESSION["logObject"]->debug("music_manager","After failure of grace-note if Old Algo also fail");
						$_SESSION["logObject"]->debug("music_manager","Then insert in unidentified fingerprints");
						unidentified_fps_handler::insert_unidentified_fp($fp_xml, $myNumber);
					}

				}	
			}
			$_SESSION["logObject"]->info("music_manager","All Songs Done!!");
			


	}
	public static function old_algo($badTitle , $badArtist , $badAlbum , $badDuration)
	{	
		return 0;
	}
	public static function proceed($fp_xml , $myNumber , $gracenote_title , $gracenote_album , $gracenote_artist ,$gracenote_genre , $gracenote_date, $duration)
	{
		

			$_SESSION["logObject"]->debug("music_manager","Assigning Song Id");
			$song_id = music_manager::assign_song_id($gracenote_title,$gracenote_album,$gracenote_artist);
			$_SESSION["logObject"]->debug("music_manager","Song Id - $song_id");
			$_SESSION["logObject"]->debug("music_manager","Album Art Fetching Started");
			$art_url = 	album_art_finder::get_album_art($gracenote_title , $gracenote_album , $gracenote_artist);
			$_SESSION["logObject"]->debug("music_manager","Will fetch Song link");
			//---->>>songLinkFetching();
			$_SESSION["logObject"]->debug("music_manager","Storing Data successfully in Database");
			music_data_handler::store_music_data($song_id , $gracenote_title , $gracenote_album , $gracenote_artist ,$gracenote_genre , $duration ,  $gracenote_date , $art_url);
			user_songs_handler::add_user_song($myNumber , $song_id , $fp_xml);
	}
	public static function assign_song_id($gracenote_title,$gracenote_album,$gracenote_artist)
	{
			$str = $gracenote_title.$gracenote_artist.$gracenote_album;
			#how many chars will be in the string
			$fill = 10;
			
			$format = '%u';
			$hash  = sprintf($format, crc32($str));
			
			
			//with str_pad function the zeros will be added
			//Making sid of 30 digits by adding 0's to left
			$hash   = str_pad($hash, $fill, '0', STR_PAD_RIGHT);

			return $hash;

	}
}


?>