<?php
require_once 'Soundcloud.php';
$client = new Services_Soundcloud('342cce7aed55518c7848985ab2ec3023', '814c1b39d957a2423d04992c0d514b2e');
$tracks = $client->get('tracks', array('q' => 'Mann Mera (Remix)', 'tags'=> 'Table No. 21' , 'streamable' =>true , 'order'=> 'hotness'));
var_dump($tracks);
?>
