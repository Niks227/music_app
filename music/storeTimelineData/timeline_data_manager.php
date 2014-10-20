<?php
/**
	* 
	*/
	class timeline_data_manager{
		
		function __construct()
		{
			
		}
		public static function run($file)
		{	
			include 'file_parser.php';
				$uid             =  file_parser::get_user_no($file);
				echo "Got the user no -" . $uid. "<br>";
				$fingerprints_array  =  file_parser::get_fingerprints($file);
				echo "Got the fingerprints array -" ;var_dump($fingerprints_array); echo "<br>";
				$flags_array         =  file_parser::get_flags($file);
				echo "Got the flags array -" ;var_dump($flags_array); echo "<br>";
			
			include 'user_songs_controller.php';
				$sids_arary = user_songs_controller::get_sids_from_fingerprints($uid , $fingerprints_array);
				var_dump($sids_arary);
				$songs_array = file_parser::get_songs($sids_arary , $fingerprints_array, $flags_array );
			
			include 'score_generator.php';
				score_generator::assign_scores($songs_array);

			include 'timeline_data_controller.php';
				timeline_data_controller::modify($uid , $songs_array);
		
		}
	}	



