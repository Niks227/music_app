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
				include 'file_parser.php';
					$uid  =  file_parser::get_user_no($file);
					echo "$uid";
				include '../music/databaseModels/contacts_data_handler.php';
					$friends = contacts_data_handler::get_wanted_friends($uid);
				include '../music/databaseModels/timeline_data_handler.php';
					$sids   = timeline_data_handler::get_friends_sids($friends);
					$scores = timeline_data_handler::get_friends_scores($friends);
					echo "SIDS- > ". $sids. "<br>";
					echo "SCORES- > ".$scores."<br>";
				include 'timeline_algo.php';
					$timeline_sids = timeline_algo::get_timeline($sids , $scores);
					var_dump($timeline_sids);
				include '../music/databaseModels/music_data_handler.php';
					foreach (array_keys($timeline_sids) as $key) {
							$all_music_info = music_data_handler::get_details($key);
					}
				include 'send_timeline.php';
					send_timeline::show($all_music_info);
				//contacts_data_handler::make_unwanted('9818715517' , '9910083985');
					
					
		}
	}
?>