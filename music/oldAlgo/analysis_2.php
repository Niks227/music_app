<?php
require_once('simple_html_dom.php');

function analysis_2($file_name,$title,$album,$artist,$duration,$u_no){
 //   echo "file_name analysis_2 is startikng----------------$file_name";
    $array_words = word_extractor($file_name);
    $file_name_space_replaced_with_plus = strtr ($file_name, array (' ' => '+'));
    $file_name =$file_name_space_replaced_with_plus;
    $link_count=-1;
    $no_of_links = sizeof($array_words);
    url_creater:
        if($link_count==-1){
 //           echo "First link bieng ";
            $url  = "http://www.last.fm/search/overview?q=".$file_name;
 //           echo "$url <br>";
        }
        else if ($link_count==$no_of_links+1) {
//            echo "<br> NO result fund <br> MOve to next analysis<br> ";
            return false;
        }
        else{
            
                $extra_part_of_link = $array_words[$link_count];
                if(strlen($extra_part_of_link)<1){
                    $link_count++;
 
                    goto url_creater;
                }
            $url  = "http://www.last.fm/search/overview?q=".$extra_part_of_link;
 //           echo "<br> $url";
            
        }
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
 //       echo "<br> NO results found move to analysis next link <br> ";
        $link_count++;
        goto url_creater;
    }
    else{
 
   //     var_dump($theData);    
        $perc_array = calculate_percentage2($file_name,$artist,$title,$theData);
        $max_perc_array = find_max_percentage2($perc_array);
        if ( $max_perc_array[0] > 64){
//                echo "<br><br> INSERTING INT DATABASE <br><BR>";
                insert_into_db2($theData,$max_perc_array[1],$u_no);
                return true;
        }
        else{
 //           echo "NO results FOund move to analysis next link";
            $link_count++;
            goto url_creater;
        }
    }
   


}
function word_extractor($file_name){
    $string = $file_name;
    $words = explode(' ', $string);
//    var_dump($words);
    $n = sizeof($words);
    return $words;
}







function insert_into_db2($theData,$pos,$u_no){
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

function find_max_percentage2($perc_array){
        $max=-999;

        $no_of_percentages = sizeof($perc_array);
   
         for($x=0; $x<$no_of_percentages ; $x++){

                if($perc_array[$x]>$max){
                        $max = $perc_array[$x];
                        $pos = $x;

                }


        }     
   //     echo "<BR> MAX : ".$max."<br>";
    //    echo "<BR> POs : $pos <br>";
        $max_perc_array[0] = $max;
        $max_perc_array[1] = $pos;
        return $max_perc_array;   

}

function calculate_percentage2($file_name,$artist,$title,$result_array)
{   


    $title = strtolower($title);
    $artist = strtolower($artist);
    $file_name = strtolower($file_name);
      $no_of_results = sizeof($result_array);
//    echo "<br> TITLE: $title";
 //   echo "<br> ARTIST: yo$artistyo";
//    echo "<br> FILE NAME: $file_name";
    
      for ($x= 0 ; $x<$no_of_results; $x++){
            $result_title  = strtolower($result_array[$x][1]);
            $result_artist = strtolower($result_array[$x][3]);  
  //          echo "<br> RESULT TITLE:". $result_array[$x][1];
 //           echo "<br> RESULT ARTIST:". $result_array[$x][3];  
            similar_text( $file_name, $result_title, $percentage1 );
            similar_text( $artist, $result_artist, $percentage2 );
     //       printf("The title are %d percent similar.", $percentage1);
     //       printf("The artists are %d percent similar.", $percentage2);
             if($artist==''){   
                 
                $final_perc = $percentage1 + 20;
            
            }
            else{
                    $final_perc= ($percentage1 + $percentage2)/2;
            
            }
      //          echo "<br> FINAL PER : $final_perc";

            $perc_array[$x] = $final_perc;        
        }
    
        return $perc_array;
}

function rectify_acc_to_duration2($duration,$result_array){
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