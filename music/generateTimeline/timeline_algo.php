<?php
	/**
	* 
	*/
	class timeline_algo
	{
		
		function __construct()
		{
		}
		public static function get_timeline($sids , $scores)
		{
					$SCORE_SIZE = 9;
					$SID_SIZE = 10;
					$sidList = array();

				

					$n = strlen($sids) / $SID_SIZE;

					for ($i = 0; $i < $n; $i++) {
						
						$_SESSION["logObject"]->info("timeline_algo","$i ITeration");
						$sid = substr($sids , $i * $SID_SIZE, $SID_SIZE);
						$_SESSION["logObject"]->debug("timeline_algo","$sid");
						//echo "<h2>$sid</h2><br>";
						if (array_key_exists($sid , $sidList)) {
							$sidList[$sid] = $sidList[$sid] + intval(substr($scores, $i * $SCORE_SIZE, $SCORE_SIZE));
						} else
							$sidList[$sid] = intval(substr($scores, $i * $SCORE_SIZE, $SCORE_SIZE));
						

					}
					arsort($sidList);
					return $sidList;

		}
	}
?>