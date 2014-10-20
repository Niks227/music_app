<?php
	/**
	* 
	*/
	class user_songs_controller{
		
		function __construct()
		{
			
		}
		public static function get_sids_from_fingerprints($user_no , $fingerprints_array)
		{		
				$sids_array = array();
				include '../music/databaseModels/user_songs_handler.php';
				foreach ($fingerprints_array as $fp) {
						$sids_array[] = user_songs_handler::get_sid($user_no , $fp);
				}
				return $sids_array;
		}
	}