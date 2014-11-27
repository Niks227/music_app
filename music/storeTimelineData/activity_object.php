<?php
/**
* 
*/
class activity_object
{
	private $postId;
	private $songId;
	private $action;
	private $streaming;
	private $ts;
	private $factor;
	private $score;

	
	function __construct($pid,$sid,$a,$s,$time)
	{
		$this->postId	   =   $pid;
		$this->songId      =   $sid;
		$this->action      =   $a;
		$this->streaming   =   $s;
		$this->ts          =   $time;
	}
	public function get_factor()
	{
		return $this->factor;
	}
	public function set_factor($f)
	{	
		$this->factor = $f;
	}
	public function get_score()
	{
		return $this->score;
	}
	public function set_score($s)
	{	
		$this->score = $s;
	}
	public function get_postId()
	{
		return $this->postId;
	}
	public function set_postId($pid)
	{	
		$this->postId = $pid;
	}

	public function get_songID()
	{
		return $this->songId;
	}
	public function set_songId($sid)
	{	
		$this->songId = $sid;
	}

	public function get_action()
	{
		return $this->action;
	}
	public function set_action($a)
	{	
		$this->action = $a;
	}
	public function get_streaming()
	{
		return $this->streaming;
	}
	public function set_streaming($s)
	{	
		$this->streaming = $s;
	}
	public function get_ts()
	{
		return $this->ts;
	}
	public function set_ts($time)
	{	
		$this->ts = $time;
	}
}
?>