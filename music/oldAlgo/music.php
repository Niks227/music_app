<?php
include 'excel_read.php';
include 'analysis_1.php';
include 'analysis_2.php';
include 'analysis_3.php';
include 'sql_connect.php';

ini_set('max_execution_time', 0);
sql_con();
//Intilizing some counts
//$rows_in_excel = extract_rows_of_excel();
//echo "hahaha";
function check_rows(){
    $querry_for_rows = "SELECT COUNT(*)  FROM or_bad_song_info";
    //echo $querry_for_rows;
     $result = mysql_query($querry_for_rows);
          while($row = mysql_fetch_array($result)) {
               $no_of_rows = $row;
          }
     return $no_of_rows[0];

} 

$no_of_rows = check_rows();
//echo $no_of_rows;
function extract_string_from_db(){
$querry = "SELECT * FROM or_bad_song_info";
$result =    mysql_query($querry);
//var_dump($result);
    while($row = mysql_fetch_array($result)) {
             $song_info_array[0] = $row['file_name'];
             $song_info_array[1] = $row['song_title'];
             $song_info_array[2] = $row['artist'];
             $song_info_array[3] = $row['album'];
             $song_info_array[4] = $row['obs_id1'];
             $song_info_array[5] = $row['userno'];
             return $song_info_array;         
       }

}
$string = extract_string_from_db();
//var_dump($string);

for ($i=1;$i<$no_of_rows;$i++){	

	//$song_info_array = extract_string_from_excel($i);
        $song_info_array = extract_string_from_db();
	 $file_name  = $song_info_array[0];
    
	$song_title  = $song_info_array[1];
	$song_artist  = $song_info_array[2];
	$song_album = $song_info_array[3];
        $obs_id1 = $song_info_array[4];
        $u_no = $song_info_array[5];
	//$song_duration = $song_info_array[7];
	$song_duration = "00:00";
	$song_album = str_replace("&nbsp;","",$song_album);
	$song_artist = str_replace("&nbsp;","",$song_artist);
	$song_title = str_replace("&nbsp;","",$song_title);

	$song_album  = check_if_string_alink($song_album);
	$song_artist = check_if_string_alink($song_artist);
	$song_title  =  check_if_string_alink($song_title);

	$file_name_without_mp3 = substr($file_name, 0, -4);
	
	
	$song_album = strtr ($song_album, array ('-' => ''));
	$song_artist = strtr ($song_artist, array ('-' => ''));
	$song_title = strtr ($song_title, array ('-' => ''));
	
	$file_name  =check_if_string_alink($file_name_without_mp3);
	// Remove special charcters
	$file_name = str_replace(' ', '-', $file_name);   
	$file_name = preg_replace('/[^A-Za-z0-9\-]/', '', $file_name);
	$file_name = str_replace('-', ' ', $file_name);
	$song_title = str_replace(' ', '-', $song_title);   
	$song_title = preg_replace('/[^A-Za-z0-9\-]/', '', $song_title);
	$song_title = str_replace('-', ' ', $song_title);
         
     //   echo "FN - $file_name <br> TI - $song_title <br>Ar-  $song_artist <br>Al-  $song_album <br>" ;	
	
// Remove numbers
	$file_name = trim(str_replace(range(0,9),'',$file_name));

	$song_album_space_replaced_with_plus = strtr ($song_album, array (' ' => '+'));
	$song_artist_space_replaced_with_plus = strtr ($song_artist, array (' ' => '+'));
	$song_title_space_replaced_with_plus = strtr ($song_title, array (' ' => '+'));
	


//	echo "<h1>".$song_title."</h1>";
	$analysis1_result=analysis_1($file_name_space_replaced_with_plus,$song_title_space_replaced_with_plus,$song_album_space_replaced_with_plus,$song_artist_space_replaced_with_plus,$song_duration,$u_no);
	//var_dump($analysis1_result);
        if($analysis1_result==true){
            $querry01 = "DELETE FROM or_bad_song_info WHERE obs_id1='$obs_id1'";
            $result =    mysql_query($querry01);
                 
        }
	else if($analysis1_result==false){
//		echo "PRoceeding to next analysis";
			$analysis2_result = analysis_2($file_name,$song_title_space_replaced_with_plus,$song_album_space_replaced_with_plus,$song_artist_space_replaced_with_plus,$song_duration,$u_no);
                       if($analysis2_result==true){
$querry02 = "DELETE FROM or_bad_song_info WHERE obs_id1='$obs_id1'";
            $result =    mysql_query($querry02);	 
                       }		
                       else if($analysis2_result==false){
//			 	echo "<H1>---ANALYSIS 3---</h1>";
			 	$analysis3_result = analysis_3($file_name,$song_title_space_replaced_with_plus,$song_album_space_replaced_with_plus,$song_artist_space_replaced_with_plus,$song_duration,$obs_id1);
			}	
	}

}
function check_if_string_alink($string){


$pattern = "/[a-zA-Z]*[:\/\/]*[A-Za-z0-9\-_]+\.+[A-Za-z0-9\.\/%&=\?\-_]+/i";
$replacement = "";
$string_without_link = preg_replace($pattern, $replacement, $string);


return $string_without_link;
}


?>