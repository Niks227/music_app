<?php
require_once('simple_html_dom.php');
/**
* 
*/
class analysis_1
{
    
    function __construct()
    {
    }
    public static function run($title,$album,$artist)
    {
        $link_count=0;
        $oldAlgoResult[]           = array();
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
        if(sizeof($theData)==0 && $link_count < 8){
    //        echo "<br> NO results found move to analysis next link <br> ";
            $link_count++;
            goto url_creater;
        }
        else{
     
           // $theData = rectify_acc_to_duration($duration,$theData);
     //       var_dump($theData);    
            $perc_array     = analysis_1::calculate_percentage($artist,$title,$theData);
            $max_perc_array = analysis_1::find_max_percentage($perc_array);
            if ( $max_perc_array[0] > 85){
      //              echo "<br><br> INSERTING INT DATABASE <br><BR>";
                    $pos                       = $max_perc_array[1];
                    
                    $$theData[$pos][1]         = str_replace("&amp;","",$theData[$pos][1]);
                    $$theData[$pos][3]         = str_replace("&amp;","",$theData[$pos][3]);
                    $oldAlgoResult['title']    = $theData[$pos][1];
                    $oldAlgoResult['artist']   = $theData[$pos][3];
                    $oldAlgoResult['duration'] = $theData[$pos][2];
                    $oldAlgoResult['status']   = TRUE;
                 //   var_dump($oldAlgoResult);
                    return $oldAlgoResult;
            }
            else{
     //           echo "NO results FOund move to analysis next link";
                $link_count++;
                goto url_creater;
            }
        }
        $oldAlgoResult['status'] = false;
        return oldAlgoResult;
    }
    public static function find_max_percentage($perc_array)
    {
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
    public static function calculate_percentage($artist,$title,$result_array)
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


        
}



?>