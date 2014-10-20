<?php
include 'loggingAndDebugging.php';
set_time_limit(0);
/**
* 
*/
class displayLog
{
	public $no;
	public $logLength;
	function __construct()
	{
				echo "<h3><u>LOG FILE</u></h3>";
				$this->no        =    loggingAndDebugging::getLatestLogFile();	
				$this->logLength =    -1;
				
	}
	public  function display()
	{
				$newFile         =    "../logs/log".$this->no.".txt";
				$newLog          =    file_get_contents($newFile, NULL, NULL, $this->logLength);
				$newLength       =    strlen($newLog);
				$this->logLength =    $this->logLength + $newLength ; 
				echo "$newLog";
		
	}


}
$log = new displayLog();


if (ob_get_level() == 0) ob_start();

for ($i = 0;; $i++){

        $log->display();
        echo str_pad('',4096)."\n";   

        ob_flush();
        flush();
        sleep(2);
}



ob_end_flush();


?>