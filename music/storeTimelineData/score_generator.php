<?php

/**
* 
*/
class score_generator
{	
	private $in_app_play_factor;
	private $out_app_play_factor;
	private $in_app_download_factor;
	private $out_app_download_factor;
	
	function __construct()
	{
	}
	public static function assign_scores($songs_array)
	{
		echo "<br>Score assigning started...<br>";
		score_generator::set_factor($songs_array);
		score_generator::scoring_algo($songs_array); 
		//Showing details
		foreach($songs_array as $s){
			echo "<br>Song id -> ". $s->get_sid();
			echo "<br>Flag -> ". $s->get_flag();
			echo "<br>Factor -> ". $s->get_factor();
			echo "<br>Score -> ". $s->get_score();
			echo "<br>";
		}
	}
	public static function  set_factor($songs_array)
	{
		echo "<br>Setting factor...<br>";
		$in_app_play_factor = 5;
		$out_app_play_factor = 10;
		$in_app_download_factor = 15;
		$out_app_download_factor = 20; 

			foreach($songs_array as $s){
				     
				    $flag = $s->get_flag();
				    if($flag==1){
				    		echo "Done!!";
				    		$factor = $in_app_play_factor;
				    }
				    else if($flag==2){
				    		echo "Done!!";
				     		$factor = $out_app_play_factor;

				    }
				    else if($flag==3){
				    		echo "Done!!";
				     		$factor = $in_app_download_factor;

				    }
				    else if($flag==4){
				    		echo "Done!!";
				     		$factor = $out_app_download_factor;

				    }
				    else{
				    	 	echo "Error -  Cant set factor";
				    	 	$factor = 0;
				    }


				    $s->set_factor($factor);
					
			}
			
	}
	public static function  scoring_algo($songs_array)
	{	$score = 10000; 
		echo "<br>Setting score...<br>";
		foreach($songs_array as $s){
			$s->set_score($score);

		}

	}
}