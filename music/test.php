<?php
 	
 	$timeOn19Nov        =  1416000002635; 
	$milliseconds       =  round(microtime(true) * 1000);;
    $modifedCurrentTime =  $milliseconds-$timeOn19Nov;
	$seconds            =  floor($modifedCurrentTime / 1000);
    $minutes            =  floor($seconds / 60);
    $hours              =  floor($minutes / 60);
    $newTimeUnit        =  floor($hours/6);
    echo " $newTimeUnit $hours $minutes $seconds";


?>