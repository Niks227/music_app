<?php
/**
* 
*/
class unidentified_fps_controller
{
    
    function __construct()
    {
    }
    public static function check_unidentified_fps($fp , $myNumber)
    {
        $found    = 1;
        $notFound = 0;
        include "../music/databaseModels/sqli_connect.php";
        $query = "SELECT user_id FROM `unidentified_fingerprints` WHERE user_id = '$myNumber' AND fingerprint = '$fp' ";        
                        
        $result = $con->query($query);
        if($result->num_rows==1){
                while ($row = $result->fetch_assoc()) {
                        $compareResults['sid'] = '0';   
                } 
                $compareResults['status'] = $found;
        }
        
        else{
                $compareResults['status'] = $notFound;
        
        }
        
        include "../music/databaseModels/sqli_close.php";
        return $compareResults;

    }
}

?>