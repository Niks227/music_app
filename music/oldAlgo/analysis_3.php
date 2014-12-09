<?php
require_once('simple_html_dom.php');

function analysis_3($file_name,$title,$album,$artist,$duration,$obs_id1){
//    echo "Analysis 3 reached";
$querry = "DELETE FROM or_bad_song_info WHERE obs_id1='$obs_id1'";
            $result =    mysql_query($querry);

}


?>