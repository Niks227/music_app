<?php
	/**
	* 
	*/
	class timeline_data_controller
	{
		
		function __construct()
		{
		}
		public static function modify($uid , $songs_array)
		{
			echo "Updating info in database";
			$new_sids ='';
			$new_scores ='';
			foreach($songs_array as $s){
				$new_sids = $new_sids . $s->get_sid();
				$new_scores = $new_scores . $s->get_score();

			}
			echo "<br> New Sids ".$new_sids;
			echo "<br> New Scores ".$new_scores."<br>";
			
			include '../music/databaseMOdels/timeline_data_handler.php';
			$result = timeline_data_handler::check_existence($uid);
			echo "Checing if user exist or a new user<br>";
			if($result==1){
				echo "User already exists<br>";


				$old_sids = timeline_data_handler::get_sids($uid);
				$old_scores = timeline_data_handler::get_scores($uid);
				echo "<br> Old Sids found  - $old_sids <br> Old Scores found - $old_scores<br>";
				timeline_data_controller::update_data($uid , $old_sids , $new_sids , $old_scores , $new_scores);
			}
			else{
				echo "User not exist<br>";
				if(strcmp($new_sids, '') != 0){

					timeline_data_handler::add_user($uid , $new_sids , $new_scores);
				}
				else
					echo "No Songs to add";
			}
		}
		public static function  update_data($uid , $old_sids , $new_sids , $old_scores , $new_scores)
		{	
				echo "<br>Updating data<br>";
				$SCORE_SIZE = 5;
				$SID_SIZE = 9;
				$all_sids = $old_sids . $new_sids;
				$all_scores = $old_scores . $new_scores;
				$sidList = array();

				

					$n = strlen($all_sids) / $SID_SIZE;

					for ($i = 0; $i < $n; $i++) {

						$sid = substr($all_sids , $i * $SID_SIZE, $SID_SIZE);

						if (array_key_exists($sid , $sidList)) {
							$sidList[$sid] = $sidList[$sid] + intval(substr($all_scores, $i * $SCORE_SIZE, $SCORE_SIZE));
						} else
							$sidList[$sid] = intval(substr($all_scores, $i * $SCORE_SIZE, $SCORE_SIZE));

					}
					$sids_string   = '';
					$scores_string = '';
					foreach (array_keys($sidList) as $key) {
						$sids_string   = $sids_string . $key;
						$scores_string = $scores_string . $sidList[$key];

					}
					echo "<br> $sids_string <br> $scores_string";
					timeline_data_handler::insert_updated_data($uid , $sids_string , $scores_string);					
			
		}


	}