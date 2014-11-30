<?php
	/**
	* 
	*/
	class timeline_view_manager
	{
		
		function __construct()
		{
		}
		public static function run($file)
		{	

				$_SESSION["logObject"]->info("timeline_view_manager","Timeline view manager running");
				include 'file_parser.php';
				$_SESSION["logObject"]->info("timeline_view_manager","Getting User no");
					$uid  =  file_parser::get_user_no($file);
				$_SESSION["logObject"]->debug("timeline_view_manager","User NO- $uid ");

					
				include '../music/databaseModels/contacts_data_handler.php';
				$_SESSION["logObject"]->info("timeline_view_manager","Getting Wanted Freiends");				
					$friends = contacts_data_handler::get_wanted_friends($uid);
				$_SESSION["logObject"]->info("timeline_view_manager","Wanted Friends found--");	
				include '../music/databaseModels/timeline_data_handler.php';

					$sids   = timeline_data_handler::get_friends_sids($friends);
					$scores = timeline_data_handler::get_friends_scores($friends);
				
					$_SESSION["logObject"]->debug("timeline_view_manager","Sids String-- $sids END");
					$_SESSION["logObject"]->debug("timeline_view_manager","Scores String $scores END");
				include 'timeline_algo.php';
					$timeline_sids = timeline_algo::get_timeline($sids , $scores);
		 			$_SESSION["logObject"]->info("timeline_view_manager","Sids for timline after algo-");
				include '../music/databaseModels/music_data_handler.php';
					$musicResponse = array();
					$musicResponse['myNumber'] = "$uid";
					$musicResponse['module'] = "generateTimeline" ;
					$musicResponse['response'] = array();
					$i        = 0 ;
					$songRank = 1 ;
					foreach (array_keys($timeline_sids) as $key) {
					//		echo $key. "<br>";
							$musicInfo = music_data_handler::get_music_details($key);
							$_SESSION["logObject"]->info("timeline_view_manager","SongRank $songRank");
							$musicResponse['response'][$i]['songRank'] = $songRank++;
							$musicResponse['response'][$i]['songId'] = intval($key);
							$musicResponse['response'][$i]['title'] = $musicInfo['title'];
							$musicResponse['response'][$i]['album'] = $musicInfo['album'];
							$musicResponse['response'][$i]['artist'] = $musicInfo['artist'];
							$musicResponse['response'][$i]['genre'] = $musicInfo['genre'];
							$musicResponse['response'][$i]['date'] = $musicInfo['date'];
							$musicResponse['response'][$i]['duration'] = $musicInfo['duration'];
							$musicResponse['response'][$i]['albumArtLink'] = $musicInfo['albumArtLink'];
							$musicResponse['response'][$i]['songLink'] = "http://2015.downloadming1.com/bollywood%20mp3/Zid%20%282014%29/01%20-%20Saanson%20Ko%20-%20DownloadMing.SE.mp3";//$musicInfo['songLink'];
							$i++;
							$q = $musicInfo['title'];
							$w = $musicInfo['album'];
                                                      $_SESSION["logObject"]->info("timeline_view_manager","Sid--  $key");
$_SESSION["logObject"]->info("timeline_view_manager","Score--  $timeline_sids[$key]");

$_SESSION["logObject"]->info("timeline_view_manager","Title-- $q ");
$_SESSION["logObject"]->info("timeline_view_manager","Album-- $w");
//$_SESSION["logObject"]->info("timeline_view_manager","Artist--  $musicInfo['artist']");
					
                                        }

					echo json_encode($musicResponse);
				
					
					
		}
	}
?>