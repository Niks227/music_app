<?php
set_time_limit(0);

/**
* 
*/
class gracenote
{
	private static $raw_info;

	function __construct()
	{
	}
        public static function gracenote_details($fp_algo , $fp_ver , $fp){
  $url = 'http://gracenote.96.lt/query.php';
$data = array('fp' => "$fp", 'fp_algo' => "$fp_algo", 'fp_ver' => "$fp_ver");

// use key 'http' even if you send the request to https://...
$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
    ),
);
$context  = stream_context_create($options);
$json = file_get_contents($url, false, $context);
  

//$json = substr($json, 0, -152);
//var_dump($json);
$json = json_decode( $json, true );
//var_dump($json);
return $json;      


         
 }



public static function original_gracenote_details($fp_algo , $fp_ver , $fp)
{


		$url = 'https://c16195584.web.cddbp.net/webapi/xml/1.0/';
$xml = "<QUERIES>
  <AUTH>
    <CLIENT>16195584-44C78BC748098943A452878771E86A6A</CLIENT>
    <USER>264678833749970017-6D2A7C447E9066206B6418B94D465CAB</USER>
  </AUTH>
<QUERY CMD=\"ALBUM_FINGERPRINT\"><MODE>SINGLE_BEST_COVER</MODE>
  <FINGERPRINT ALGORITHM=\"$fp_algo\" VERSION=\"$fp_ver\" >
    <DATA>$fp</DATA>
  </FINGERPRINT>
</QUERY>
</QUERIES>";
echo "<br><br>".$xml;

 //setting the curl parameters.
 $headers = array(
    "Content-type: text/xml;charset=\"utf-8\"",
    "Accept: text/xml",
    "Cache-Control: no-cache",
    "Pragma: no-cache",
    "SOAPAction: \"run\""
 );

        try{
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);

            // send xml request to a server

      //      curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
      //      curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);

            curl_setopt($ch, CURLOPT_POSTFIELDS,  $xml);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            curl_setopt($ch, CURLOPT_SSLVERSION,3);
            curl_setopt($ch, CURLOPT_VERBOSE, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $data = curl_exec($ch);

            //convert the XML result into array
            if($data === false){
                $error = curl_error($ch);
                echo $error; 
                die('error occured');
            }else{

                $data = simplexml_load_string($data);  
            }
            curl_close($ch);
            
         // 	  var_dump($data);
        //    echo "<br>Gracenote ARTIST --->".$data->RESPONSE->ALBUM->TRACK->ARTIST;
		//	echo "<br>Gracenote TITLE --->".$data->RESPONSE->ALBUM->TRACK->TITLE;
	    //	echo "<br>Gracenote URL --->".$data->RESPONSE->ALBUM->URL;
            return $data;

        }catch(Exception  $e){
            echo 'Message: ' .$e->getMessage();die("Error");
    }
			



	}
	
}
?>