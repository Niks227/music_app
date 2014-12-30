<?php
/**
* 
*/
class linkManager
{
	
	function __construct()
	{
	}
	public static function run($title, $album , $artist, $duration, $date)
	{
		require_once 'Soundcloud.php';
		$trackNo   =  linkManager::searchTrackNo($title, $album , $artist, $duration, $date);
		$apiLink   =  linkManager::fetchLink($trackNo);
		$songLink  =  linkManager::addClientId($apiLink);
		return $songLink;
	}
	public static function searchTrackNo($title, $album , $artist, $duration, $date)
	{

	}
	public static function fetchLink($trackNo)
	{
		
	}
	public static function addClientId($apiLink)
	{

	}
}
?>