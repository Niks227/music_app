<?php
require_once 'Soundcloud.php';


$client = new Services_Soundcloud('342cce7aed55518c7848985ab2ec3023', '814c1b39d957a2423d04992c0d514b2e');
$tracks = $client->get('tracks', array('q' => 'mitti di khushboo', 'tags'=> '', 'limit' => '9' , 'streamable' =>'true' , 'order'=> 'hotness'));

$tracks_array  =  array();
$tracks_array  =  json_decode($tracks);
$i=0;
foreach ($tracks_array as $key => $value) {
	//var_dump($tracks_array[$i]->streamable);
	if($tracks_array[$i]->streamable == true){
		echo $tracks_array[$i]->stream_url."?client_id=342cce7aed55518c7848985ab2ec3023";
		break;
	}
	$i++;
}
?>
