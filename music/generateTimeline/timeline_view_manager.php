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
					$myNumber  =  file_parser::get_user_no($file);
				$_SESSION["logObject"]->debug("timeline_view_manager","User NO- $myNumber ");

					
				include '../music/databaseModels/contacts_data_handler.php';
				$_SESSION["logObject"]->info("timeline_view_manager","Getting Wanted Freiends");				
					$friendsArray = contacts_data_handler::get_wanted_friends($myNumber);
					$friends = timeline_view_manager::arrayToString($friendsArray);

							
				
				$_SESSION["logObject"]->info("timeline_view_manager","Wanted Friends found-- $friends");	
				include '../music/databaseModels/timeline_data_handler.php';
					$sidsScores  = array();
					$sidsScores  = timeline_data_handler::get_friends_data($friends);
					
					$sids  = $sidsScores['sids'];
					$scores = $sidsScores['scores'];
					
						
					$_SESSION["logObject"]->debug("timeline_view_manager","Sids String-- $sids END");
					$_SESSION["logObject"]->debug("timeline_view_manager","Scores String $scores END");
				include 'timeline_algo.php';
					$timeline_sids = timeline_algo::get_timeline($sids , $scores);
		 			$_SESSION["logObject"]->info("timeline_view_manager","Sorted Information for timline -");
				include '../music/databaseModels/music_data_handler.php';
					$musicResponse = array();
					$musicResponse['response'] = array();
					$i        = 0 ;
					$songRank = 1 ;
					foreach (array_keys($timeline_sids) as $key) {
							$musicInfo = music_data_handler::get_music_details($key);
							if(strcmp($musicInfo['available'],"true")==0){
									$_SESSION["logObject"]->info("timeline_view_manager","SongRank $songRank");
									$musicResponse['response'][$i]['songRank']     = $songRank++;
									$musicResponse['response'][$i]['songId']       = intval($key);
									$musicResponse['response'][$i]['title']        = $musicInfo['title'];
									$musicResponse['response'][$i]['album']        = $musicInfo['album'];
									$musicResponse['response'][$i]['artist']       = $musicInfo['artist'];
									$musicResponse['response'][$i]['genre']        = $musicInfo['genre'];
									$musicResponse['response'][$i]['date']         = $musicInfo['date'];
									$musicResponse['response'][$i]['duration']     = $musicInfo['duration'];
									$musicResponse['response'][$i]['albumArtLink'] = $musicInfo['albumArtLink'];
									$musicResponse['response'][$i]['songLink']     = $musicInfo['songLink'];
									
									$i++;


									$q = $musicInfo['title'];
									
		                            $_SESSION["logObject"]->info("timeline_view_manager","Sid--  $key");
									$_SESSION["logObject"]->info("timeline_view_manager","Score--  $timeline_sids[$key]");
									$_SESSION["logObject"]->info("timeline_view_manager","Title-- $q ");
								
									

							}
							
					}
					
					echo json_encode($musicResponse);
				
					
					
		}
		public static function arrayToString($array)
		{	
				$string = "'";
				include "../music/databaseModels/sqli_connect.php";
				foreach ($array as $key => $value){
						$value  = $con->real_escape_string($value);  
						$string = $string . $value. "','";
				}
				include "../music/databaseModels/sqli_close.php";
				$string = rtrim($string, ",'");
				$string =$string."'";
				return $string;

		}
	}
?>