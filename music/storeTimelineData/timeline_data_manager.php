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
				$activityObjectsArray = array();
				$uid                  =  file_parser::get_user_no($file);
				$_SESSION["logObject"]->info("timeline_data_manager","Timeline data manager running");
				$_SESSION["logObject"]->info("timeline_data_manager","Got the user no - $uid ");
				$activityObjectsArray = file_parser::getActivityObjects($file);

			include 'score_generator.php';
				$_SESSION["logObject"]->info("timeline_data_manager","Assigning Scores");
				foreach ($activityObjectsArray as $key => $value) {
						$_SESSION["logObject"]->info("timeline_data_manager","New Activity Object");
						score_generator::assign_scores($value);

				}
			include 'timeline_data_controller.php';
				$result = timeline_data_controller::modify($uid , $activityObjectsArray);
				
				echo json_encode($result);
		}
	}	



