<?php

/**
* 
*/
class user_songs_controller
{
    
    function __construct()
    {
    }
    public static function check_db($fp, $myNumber)
    {
        $found    = 1;
        $notFound = 0;
        include "../music/databaseModels/sqli_connect.php";
        $query = "SELECT song_id FROM `user_songs` WHERE user_id = '$myNumber' AND fingerprint = '$fp' ";       
                        
        $result = $con->query($query);
        if($result->num_rows==1){
                while ($row = $result->fetch_assoc()) {
                        $compareResults['sid'] = $row['song_id'];   
                } 
                $compareResults['status'] = $found;
        }
        
        else{
                $compareResults['status'] = $notFound;
        
        }

        include "../music/databaseModels/sqli_close.php";
        return $compareResults;

    }

    public static function get_music_info($myNumber)
    {
        $response_song_no = 0;
        $music_list_response = array();
        $myNumber = addslashes($myNumber);  
        include "../music/databaseModels/sqli_connect.php";

        $query = "SELECT music.song_id , `title`, `artist`, `album`, `genre`, `date`, `duration`, `album_art_link`, `user_id`, `fingerprint` FROM `music` , `user_songs` WHERE user_songs.song_id = music.song_id AND user_id = '$myNumber'";       
                        
        $result = $con->query($query);


        include "../music/databaseModels/sqli_close.php";

        if (!$result) {
                $music_list_response['error'] = 'Sql querry unsucessfull';

        }
        else if ($result->num_rows== 0) {
                $music_list_response['songData'] = array();
                
        }
        else{
                

                while ($row = $result->fetch_assoc()) {

                        $music_list_response['songData'][$response_song_no]['id']               = $row['song_id'];
                        $music_list_response['songData'][$response_song_no]['title']            = $row['title'];
                        $music_list_response['songData'][$response_song_no]['artist']           = $row['artist'];
                        $music_list_response['songData'][$response_song_no]['album']            = $row['album'];
                        $music_list_response['songData'][$response_song_no]['genre']            = $row['genre'];
                        $music_list_response['songData'][$response_song_no]['date']             = $row['date'];
                        $music_list_response['songData'][$response_song_no]['duration']         = $row['duration'];
                        $music_list_response['songData'][$response_song_no]['album_art_link']   = $row['album_art_link'];
                        $music_list_response['songData'][$response_song_no]['fingerprint']      = $row['fingerprint'];
                        
                        $response_song_no++;
                } 
                
                
        }
        return $music_list_response;



            
    }

}



?>