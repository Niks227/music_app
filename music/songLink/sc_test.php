<?php
require_once 'Soundcloud.php';
$soundcloud = new Services_Soundcloud('342cce7aed55518c7848985ab2ec3023', '814c1b39d957a2423d04992c0d514b2e', 'http://localhost/sc/php/Services/sc_test.php');

$authorizeUrl = $soundcloud->getAuthorizeUrl();

?> 
<a href="<?php echo $authorizeUrl; ?>">Connect with SoundCloud</a> 
<?php
    if(isset($_GET['code'])){     
            echo "Got code"; 

            try {         
                $accessToken = $soundcloud->accessToken($_GET['code'], array(     CURLOPT_SSL_VERIFYPEER =>
                false,     CURLOPT_SSL_VERIFYHOST => false, ));     
            } 
            catch(Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
                exit($e->getMessage());     
            }     
       /*     try {     
                $me = json_decode($soundcloud->get('me'), true);     
                var_dump($me); 
            } 
            catch(Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
                exit($e->getMessage()); 
            } 
         */
            try {
                $track = $soundcloud->download(87222937);
             //   var_dump($track);
            } catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
                exit($e->getMessage());
            }
        }    

?>