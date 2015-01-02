<?php

/**
* 
*/
class score_generator
{	
	private $IN_APP_PLAY_FACTOR;
	private $OUT_APP_PLAY_FACTOR;
	private $IN_APP_DOWNLOAD_FACTOR;
	private $OUT_APP_DOWNLOAD_FACTOR;
	
	function __construct()
	{
	}
	public static function assign_scores($activityObject)
	{
		score_generator::set_factor($activityObject);
		score_generator::scoring_algo($activityObject); 
	}
	public static function  set_factor($activityObject)
	{
		$IN_APP_PLAY_FACTOR      = 5;
		$OUT_APP_PLAY_FACTOR     = 10;
		$IN_APP_DOWNLOAD_FACTOR  = 15;
		$OUT_APP_DOWNLOAD_FACTOR = 20; 
				    $action = $activityObject->get_action();
				    $_SESSION["logObject"]->debug("score_generator","Flag Found-- $action");
				    if(strcmp( $action,"inAppPlay")==0){
				    		$factor = $IN_APP_PLAY_FACTOR;

					}
				    else if(strcmp( $action,"outAppPlay")==0){
				     		$factor = $OUT_APP_PLAY_FACTOR;

				    }
				    else if(strcmp( $action,"inAppDownload")==0){
				     		$factor = $IN_APP_DOWNLOAD_FACTOR;

				    }
				    else if(strcmp( $action,"outAppDownload")==0){
				     		$factor = $OUT_APP_DOWNLOAD_FACTOR;

				    }
				    else{
				    	 	$_SESSION["logObject"]->error("score_generator","Cant set factor");
				    	 	$factor = 0;
				    }


				    $activityObject->set_factor($factor);
				    $_SESSION["logObject"]->debug("score_generator","Factor assigned -- $factor");
					
			
	}
	public static function  scoring_algo($activityObject)
	{	
			$_SESSION["logObject"]->info("score_generator","Scoring from factor");
			$timeFactor = score_generator::get_time_unit( $activityObject->get_ts() );		
			$score = $activityObject->get_factor() * $timeFactor;
			$score = str_pad($score, 9, 0, STR_PAD_LEFT);
			$_SESSION["logObject"]->debug("score_generator","Score assigned -- $score"); 
			$activityObject->set_score($score);
	}
	public static function get_time_unit($milliseconds)
	{
		 	$timeOn19Nov        =  1416000002635; 
		    $modifedCurrentTime =  $milliseconds-$timeOn19Nov;
			$seconds            =  floor($modifedCurrentTime / 1000);
		    $minutes            =  floor($seconds / 60);
		    $hours              =  floor($minutes / 60);
		    $newTimeUnit        =  floor($hours / 6);
		    $_SESSION["logObject"]->debug("score_generator","New Time Unit-- $newTimeUnit");
		    
		    return $newTimeUnit;

	}

}