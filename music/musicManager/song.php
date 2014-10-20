<?php

/**
* 
*/
class song 
{	
	private $sid;
	private $fingerprint;
	private $flag;
	private $factor;
	private $score;
	

	
	function __construct($s,$fp,$fl)
	{			
		$this->sid	     =	$s;
		$this->fingerprint = $fp;
		$this->flag        = $fl;
	}
	public function get_sid()
	{
		return $this->sid;
	}
	public function get_score()
	{
		return $this->score;
	}
	public function get_flag()
	{
		return $this->flag;
	}
	public function set_factor($factor)
	{	
		$this->factor = $factor;

	}
	public function get_factor()
	{
		return $this->factor;
	}
	
	public function set_score($score)
	{	
		$this->score = $score;

	}

}