<?php
/**
* 
*/
class generateLog
{
	public static $myfile;	
	function __construct()
	{   
				
				$no = generateLog::getLatestLogFile();	
				$no++; //Incement Log File No
				$timeStamp = generateLog::getTime();
				$newFile = "../logs/log".$no.".txt";
				generateLog::$myfile = fopen($newFile, "w") or die("Unable to open file!");
				$txt = "<font color=\"green\"><Info>    $timeStamp --->> New Log File Created</font><br>\n";
				fwrite(generateLog::$myfile, $txt);
	}
	public function info($tag,$string)
	{
				$timeStamp = generateLog::getTime();
				$txt = "<font color=\"green\"><Info>   $timeStamp --->> ($tag) $string</font><br>\n";
				fwrite(generateLog::$myfile, $txt);
	}
	public function debug($tag,$string)
	{
				$timeStamp = generateLog::getTime();
				$txt = "<font color=\"blue\"><Debug>   $timeStamp --->> ($tag) $string</font><br>\n";
				fwrite(generateLog::$myfile, $txt);
	}
	public function error($tag,$string)
	{
				$timeStamp = generateLog::getTime();
				$txt = "<font color=\"red\"><Error>   $timeStamp --->> ($tag) $string</font><br>\n";
				fwrite(generateLog::$myfile, $txt);
	}
	public function end($tag,$string)
	{
				$timeStamp = generateLog::getTime();
				$txt = "<font color=\"orange\"><Warning> $timeStamp --->> ($tag) $string</font><br>\n";
				fwrite(generateLog::$myfile, $txt);
	}
	public static function getLatestLogFile()
	{
				$i = 0;  
				while(true){
	 				$filename = '../logs/log'.$i.'.txt';

					if (file_exists($filename)) {
					 //   echo "<BR>The file $filename exists";
					    $i++;
					} 
					else {
					//    echo "<br>The file $filename does not exist";
					    break;
					}
				
				}
				$i--;
				return $i;
		
	}
	public static function getTime()
	{
				date_default_timezone_set('Asia/Calcutta');
				$date = date('m/d/Y h:i:s a', time());
				return $date;
	}
}


?>