<?php
require_once('simple_html_dom.php');

function analysis_1($file_name,$title,$album,$artist,$duration,$u_no){
$link_count=0;
 url_creater:

    if($link_count==0){
   //     echo "First link bieng ";
        $url  = "http://www.last.fm/search/overview?q=".$title."+".$artist;
    }
    else if($link_count==1){
   //     echo "second link";
        $url  = "http://www.last.fm/search/overview?q=".$title;
    }
    else if($link_count==2){
    //    echo "third link";
        $url  = "http://www.last.fm/search/overview?q=".$artist;
    }
    else if($link_count==3){
   //     echo "fourth link";
        $url  = "http://www.last.fm/search/overview?q=".$title."+".$album."+".$artist;
    }
    else if($link_count==4){
     //   echo "fifth link";

        $url  = "http://www.last.fm/search/overview?q=".$artist."+".$album;
    }
    else if($link_count==5){
    //    echo "sixth link";
        $url  = "http://www.last.fm/search/overview?q=".$title."+".$album;
    }
    else if($link_count==6){
    //    echo "7th link";
        $url  = "http://www.last.fm/search/overview?q=".$album;
    }
    else
            return FALSE;  
 //   echo "$url";
    $html = file_get_html($url);
    $i=0;
   
    // initialize empty array to store the data array from each row
    $theData = array();
    foreach ($html->find('.candyStriped') as $table) {
        // loop over rows
        foreach($table->find('tr') as $row) {

            // initialize array to store the cell data from each row
            $rowData = array();
            foreach($row->find('td') as $cell) {

                // push the cell's text to the array
                $rowData[] = trim($cell->plaintext);

            }   

            // push the row's data array to the 'big' array
            $theData[] = $rowData;
        }
    }
    if(sizeof($theData)==0){
//        echo "<br> NO results found move to analysis next link <br> ";
        $link_count++;
        goto url_creater;
    }
    else{
 
       // $theData = rectify_acc_to_duration($duration,$theData);
 //       var_dump($theData);    
        $perc_array = calculate_percentage($artist,$title,$theData);
        $max_perc_array = find_max_percentage($perc_array);
        if ( $max_perc_array[0] > 68){
  //              echo "<br><br> INSERTING INT DATABASE <br><BR>";
                insert_into_db($theData,$max_perc_array[1],$u_no);
                return TRUE;
        }
        else{
 //           echo "NO results FOund move to analysis next link";
            $link_count++;
            goto url_creater;
        }
    }
	   
}

function insert_into_db($theData,$pos,$u_no){
    sql_con();
    $title =  $theData[$pos][1];
    $artist = $theData[$pos][3];
    $duration = $theData[$pos][2];

    $title = str_replace("&amp;","",$title);
    $artist = str_replace("&amp;","",$artist);
    $hash= $title.$artist.$duration;
    $s_id = md5($hash);
    $hash2= $u_no.$s_id;
    $us_id = md5($hash2); 
    

$querry = "INSERT INTO song_info (s_id, song_title, artist, duration)
VALUES ('$s_id','$title', '$artist','$duration')";
 
    mysql_query($querry);

   $querry2 = "INSERT INTO users_info (us_id, u_no, s_id)
VALUES ('$us_id','$u_no', '$s_id')";
 
    mysql_query($querry2);

    
 

}

function find_max_percentage($perc_array){
        $max=-999;

        $no_of_percentages = sizeof($perc_array);
   
         for($x=0; $x<$no_of_percentages ; $x++){

                if($perc_array[$x]>$max){
                        $max = $perc_array[$x];
                        $pos = $x;

                }


        }     
 //       echo "<BR> MAX : ".$max."<br>";
 //       echo "<BR> POs : $pos <br>";
        $max_perc_array[0] = $max;
        $max_perc_array[1] = $pos;
        return $max_perc_array;   

}

function calculate_percentage($artist,$title,$result_array)
{   


    $title = strtolower($title);
    $artist = strtolower($artist);
      $no_of_results = sizeof($result_array);
 //   echo "<br> TITLE: $title";
  //  echo "<br> ARTIST: $artist";
      for ($x= 0 ; $x<$no_of_results; $x++){
            $result_title  = strtolower($result_array[$x][1]);
            $result_artist = strtolower($result_array[$x][3]);  
  //          echo "<br> RESULT TITLE:". $result_array[$x][1];
 //           echo "<br> RESULT ARTIST:". $result_array[$x][3];  
            similar_text( $title, $result_title, $percentage1 );
            similar_text( $artist, $result_artist, $percentage2 );
 //           printf("The title are %d percent similar.", $percentage1);
 //           printf("The artists are %d percent similar.", $percentage2);
             if($artist==''){   
                 
                $final_perc = $percentage1 + 20;
            
            }
            else if($title==''){

                $final_perc = $percentage2 + 20;
            }
            else{
                    $final_perc= ($percentage1 + $percentage2)/2;
            
            }
 //               echo "<br> FINAL PER : $final_perc";

            $perc_array[$x] = $final_perc;        
        }
    
        return $perc_array;
}

function rectify_acc_to_duration($duration,$result_array){
    $no_of_results = sizeof($result_array);
    $k=0;
    sscanf($duration, "%d:%d:%d", $hours, $minutes, $seconds);
    $duration_time_seconds = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;
        for($i=0;$i<$no_of_results;$i++){
            $B = $result_array[$i][2];

            sscanf($B, "%d:%d:%d", $hours, $minutes, $seconds);
            $results_time_seconds = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;
        if ($duration_time_seconds - $results_time_seconds <= 10 && $duration_time_seconds - $results_time_seconds >0)  {
               $new_results[$k] = $result_array [$i]; 
                $k++;
        }
        else if ($results_time_seconds - $duration_time_seconds <= 10  && $results_time_seconds - $duration_time_seconds > 0) {
               $new_results[$k] = $result_array [$i];
                $k++;
        }
        else {
            
        }

   }

   return $new_results;
}
?>