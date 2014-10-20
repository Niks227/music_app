<?php
global $log;
/**
* 
*/
class music_manager
{
	
	function __construct()
	{	$log->info("music_manager","Music Manager Started its Work");
            
	}
	public static function run($file)
	{	
		
		include 'gracenote.php';
		include 'file_parser.php';
		include 'album_art_finder.php';
		include 'user_songs_controller.php';
		include 'unidentified_fps_controller.php';
		include '../music/databaseModels/music_data_handler.php';
		include '../music/databaseModels/user_songs_handler.php';
		include '../music/databaseModels/unidentified_fps_handler.php';


		$myNumber = file_parser::get_myNumber($file);
		$cmd = file_parser::get_cmd($file);
		
		switch ($cmd) {
			case "add":
			//	echo "<br>Started adding songs";
				music_manager::add_songs($file,$myNumber);	

				break;
			case "delete":
			//	echo "Started deleting songs";
				music_manager::delete_songs($file,$myNumber);

				break;
			case "getSongsList":
			//	echo "<br>Getting songs";
				music_manager::get_songs_list($myNumber);

				break;

			case "getSongStatus":
			//	echo "<br>Getting Song Status";
				music_manager::get_song_status($file,$myNumber);
				
				break;
			default:
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
			music_manager::freeUserBrowser($response);
			$songs_array = file_parser::get_songs_array($file);
		
			foreach ($songs_array as $song) {
				
				$fp_xml = $song['xml'];
			
				$fp_algo = $song['fingerprint_algo'];

				$fp_ver = $song['fingerprint_version'];
				$fp = $song['fingerprint'];
				$duration = $song['duration']; 
			
				echo "<BR><h1>NewSong</h1>";
				$data = gracenote::gracenote_details($fp_algo , $fp_ver , $fp);
				//var_dump($data);

                               
				if(strcmp($data["RESPONSE"]["@attributes"]["STATUS"],"OK")==0){
					
					echo "<br>Gracnote worked.. <h2> :) </h2>";
					
					$gracenote_title = file_parser::get_gracenote_title($data);
					$gracenote_album = file_parser::get_gracenote_album($data);
					$gracenote_artist = file_parser::get_gracenote_artist($data);
					$gracenote_genre = file_parser::get_gracenote_genre($data);
					$gracenote_date = file_parser::get_gracenote_date($data);
						
					music_manager::proceed($fp_xml , $myNumber,$gracenote_title , $gracenote_album , $gracenote_artist ,$gracenote_genre , $gracenote_date , $duration);

				}
				else{
					echo "<br>Granote could not work.. <h2> :( </h2>";
//					old_algo();
//					if(old_algo_status=='ok'){
//							proceed();
//					}
//					else{
//						echo "stroe incomlete in table";
						unidentified_fps_handler::insert_unidentified_fp($fp_xml, $myNumber);
//					}

				}	
			}


	}
	public static function proceed($fp_xml , $myNumber , $gracenote_title , $gracenote_album , $gracenote_artist ,$gracenote_genre , $gracenote_date, $duration)
	{
		

			$song_id = music_manager::assign_song_id($gracenote_title,$gracenote_album,$gracenote_artist);
			echo "<br>Song ID-->".$song_id."<br>";
			$art_url = 	album_art_finder::get_album_art($gracenote_title , $gracenote_album , $gracenote_artist);
			//---->>>songLinkFetching();
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