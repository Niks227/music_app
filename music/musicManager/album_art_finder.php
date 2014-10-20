<?php

/**
* 
*/
class album_art_finder
{
    private $title;
    private $artist;
    private $album;
    
    function __construct()
    {
    }
    public static function get_album_art($gracenote_title , $gracenote_album , $gracenote_artist){
            
            $link = album_art_finder::get_itunes_art($gracenote_title , $gracenote_album , $gracenote_artist);
            return $link;

    }
    public static function get_itunes_art($gracenote_title , $gracenote_album , $gracenote_artist)
    {   
            $art_url ='';
            $search = $gracenote_album;
            $url = 'http://ax.itunes.apple.com/WebObjects/MZStoreServices.woa/wa/wsSearch?term='.urlencode($search).'&limit=1';
            $obj = json_decode(file_get_contents($url));
            $results = array();
            foreach ($obj->results as $result) {
                    $data = array();
                    $data['url'] = str_replace('100x100', '600x600', $result->artworkUrl100);
                    $hires = str_replace('.100x100-75', '', $result->artworkUrl100);
                    $parts = parse_url($hires);
                    $hires = 'http://a4.mzstatic.com'.$parts['path'];
                    $data['hires'] = $hires;
                    $data['hires'] = str_replace('100x100', '1200x1200', $result->artworkUrl100);
                    $results[] = $data;
                    var_dump($data);
                    $art_url = $results[0]['hires'];
                    echo "<br>itunes art--->>>>".$art_url."<br>";
                        
                    return $art_url;  
            }
            if(strcmp($art_url,'')==0){
                    $search = $gracenote_title;
                    $url = 'http://ax.itunes.apple.com/WebObjects/MZStoreServices.woa/wa/wsSearch?term='.urlencode($search).'&limit=1';
                    $obj = json_decode(file_get_contents($url));
                    $results = array();
                    foreach ($obj->results as $result) {
                            $data = array();
                            $data['url'] = str_replace('100x100', '600x600', $result->artworkUrl100);
                            $hires = str_replace('.100x100-75', '', $result->artworkUrl100);
                            $parts = parse_url($hires);
                            $hires = 'http://a4.mzstatic.com'.$parts['path'];
                            $data['hires'] = $hires;
                            $data['hires'] = str_replace('100x100', '1200x1200', $result->artworkUrl100);
                            $results[] = $data;
                            var_dump($data);
                            $art_url = $results[0]['hires'];
                            echo "<br>itunes art--->>>>".$art_url."<br>";
                                
                            return $art_url;  
                    }
            }
                            
            

            

    }

}

?>