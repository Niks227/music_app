<?php
/**
* 
*/
class oldAlgoReciver 
{
	
	function __construct()
	{
	}
	public static function run($badTitle , $badArtist , $badAlbum , $badDuration)
	{
		$song_title  =  $badTitle;
		$song_artist =  $badArtist;
		$song_album  =  $badAlbum;

		$song_album  = str_replace("&nbsp;","",$song_album);
		$song_artist = str_replace("&nbsp;","",$song_artist);
		$song_title  = str_replace("&nbsp;","",$song_title);

		$song_album  = oldAlgoReciver::check_if_string_alink($song_album);
		$song_artist = oldAlgoReciver::check_if_string_alink($song_artist);
		$song_title  = oldAlgoReciver::check_if_string_alink($song_title);
		
		$song_album  = strtr ($song_album, array ('-' => ''));
		$song_artist = strtr ($song_artist, array ('-' => ''));
		$song_title  = strtr ($song_title, array ('-' => ''));

		$song_title  = str_replace(' ', '-', $song_title);   
		$song_title  = preg_replace('/[^A-Za-z0-9\-]/', '', $song_title);
		$song_title  = str_replace('-', ' ', $song_title);
		//srwp=>String replaceed with plus
		$song_album_srwp  = strtr ($song_album, array (' ' => '+'));
		$song_artist_srwp = strtr ($song_artist, array (' ' => '+'));
		$song_title_srwp  = strtr ($song_title, array (' ' => '+'));

 
		$analysis1_result = array();
		$analysis1_result    =  analysis_1::run( $song_title_srwp , $song_album_srwp , $song_artist_srwp);
	    if($analysis1_result['status'] == true){
                 return $analysis1_result;
        }
        else{
        	$a['status'] = false;
        	return $a;
        }
	/*	else if($analysis1_result==false){
			$analysis2_result = analysis_2($song_title_space_replaced_with_plus,$song_album_space_replaced_with_plus,$song_artist_space_replaced_with_plus);
            if($analysis2_result ==true){
					//$querry02 = "DELETE FROM or_bad_song_info WHERE obs_id1='$obs_id1'";
            		//$result =    mysql_query($querry02);
            		echo "analysis 2 success";	 
            }		
            else if($analysis2_result ==false){
				 	$analysis3_result = analysis_3($song_title_space_replaced_with_plus,$song_album_space_replaced_with_plus,$song_artist_space_replaced_with_plus);
					echo "analysis 3 success";
			}	
		}
	*/
	}
	public static function check_if_string_alink($string)
	{	
		$pattern = "/[a-zA-Z]*[:\/\/]*[A-Za-z0-9\-_]+\.+[A-Za-z0-9\.\/%&=\?\-_]+/i";
		$replacement = "";
		$string_without_link = preg_replace($pattern, $replacement, $string);
		return $string_without_link;
	}
}

?>