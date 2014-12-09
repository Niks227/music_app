<?php
/**
* 
*/
class errorHandling
{	
	PRIVATE  $ERROR_MSG;
	PRIVATE  $ERROR_CODE;
	PRIVATE  $LOG_FILE_NO;	
	
	function __construct($code , $msg, $log)
	{
			$this->ERROR_CODE  = $code;
			$this->ERROR_MSG   = $msg;
			$this->LOG_FILE_NO = $log; 
	}
	public function send()
	{
		
			$arr = array ('errorCode'=>"$this->ERROR_CODE",'errorMessage'=>"$this->ERROR_MSG");
		    echo json_encode($arr);
			errorHandling::tempMail('niksagg227@gmail.com', 'Social Music Error Reportiong' , "ERROR CODE-- <$this->ERROR_CODE>          ERROR MESSAGE-- <$this->ERROR_MSG> LOG FILE-- <$this->LOG_FILE_NO>" );
			exit();

	}
	public static function tempMail($to , $subject , $message)
	{

			$url = 'http://gracenote.comule.com/mailing.php';
			$data = array('to' => $to, 'subject' => $subject, 'message' => $message);

			// use key 'http' even if you send the request to https://...
			$options = array(
			    'http' => array(
			        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			        'method'  => 'POST',
			        'content' => http_build_query($data),
			    ),
			);
			$context  = stream_context_create($options);
			$result = file_get_contents($url, false, $context);
			return $result;
	}


}


?>