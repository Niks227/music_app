<?php
	/**
	* 
	*/
	class timeline_data_controller
	{
		private $sqlStatus;
		function __construct()
		{

			$this->sqlStatus = 0;
	
		}
		public static function modify($uid , $activityObjects)
		{
			$new_sids ='';
			$new_scores ='';
			$replyUserAvtivity = array();
			$replyUserAvtivity['myNumber'] = $uid;
			$replyUserAvtivity['module'] = "storeTimelineData";
			$replyUserAvtivity['response'] = array();
			$i = 0;
			foreach ($activityObjects as $key => $object) {
				if($object->get_streaming()==0){
						$new_sids   =  $new_sids   . $object->get_songId();
						$new_scores =  $new_scores . $object->get_score();
						$replyUserAvtivity['response'][$i]["postId"] = $object->get_postId();
						$replyUserAvtivity['response'][$i]["songStatus"] = 1;
						$i++;
				}
				elseif ($object->get_streaming()==1) {
						echo "sound CLoud";
						$replyUserAvtivity['response'][$i]["postId"] = $object->get_postId();
						$replyUserAvtivity['response'][$i]["songStatus"] = 0;
						$i++;
				}	
			}
			echo "<br> New Sids ".$new_sids;
			echo "<br> New Scores ".$new_scores."<br>";
			
			include '../music/databaseModels/timeline_data_handler.php';
			$result = timeline_data_handler::check_existence($uid);
			echo "Checing if user exist or a new user<br>";
			if($result==1){
				echo "User already exists<br>";
				$old_sids = timeline_data_handler::get_sids($uid);
				$old_scores = timeline_data_handler::get_scores($uid);
				echo "<br> Old Sids found  - $old_sids <br> Old Scores found - $old_scores<br>";
				$sqlStatus = timeline_data_controller::update_data($uid , $old_sids , $new_sids , $old_scores , $new_scores);
			}
			else{
				echo "User not exist<br>";
				if(strcmp($new_sids, '') != 0){
						$sqlStatus =timeline_data_handler::add_user($uid , $new_sids , $new_scores);
						echo $sqlStatus;
				}
				else{
					echo "No Songs to add";
					$sqlStatus = 0;
				}
			} 

			foreach ($replyUserAvtivity['response'] as $key => $value) {
						$replyUserAvtivity['response'][$key]['songStatus'] = intval($value['songStatus'] && $sqlStatus);			
			}
			return $replyUserAvtivity;
		}
		public static function  update_data($uid , $old_sids , $new_sids , $old_scores , $new_scores)
		{	
				echo "<br>Updating data<br>";
				$SCORE_SIZE = 9;
				$SID_SIZE = 10;
				$all_sids = $old_sids . $new_sids;
				$all_scores = $old_scores . $new_scores;
				$sidList = array();

				

					$n = strlen($all_sids) / $SID_SIZE;

					for ($i = 0; $i < $n; $i++) {

						$sid = substr($all_sids , $i * $SID_SIZE, $SID_SIZE);

						if (array_key_exists($sid , $sidList)) {
							$sidList[$sid] = $sidList[$sid] + intval(substr($all_scores, $i * $SCORE_SIZE, $SCORE_SIZE));
							$sidList[$sid] = str_pad($sidList[$sid], 9, 0, STR_PAD_LEFT);
						} 
						else{
							$sidList[$sid] = intval(substr($all_scores, $i * $SCORE_SIZE, $SCORE_SIZE));
							$sidList[$sid] = str_pad($sidList[$sid], 9, 0, STR_PAD_LEFT);
						}	
					}
					$sids_string   = '';
					$scores_string = '';
					foreach (array_keys($sidList) as $key) {
						$sids_string   = $sids_string . $key;
						$scores_string = $scores_string . $sidList[$key];

					}
					echo "<br> $sids_string <br> $scores_string";
					$sqlStatus = timeline_data_handler::insert_updated_data($uid , $sids_string , $scores_string);					
					echo "$sqlStatus";
					return $sqlStatus;
		}


	}