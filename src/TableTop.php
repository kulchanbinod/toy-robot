<?php 
namespace ToyRobot;

/**
 * Table top class
 */
class TableTop
{
	// use to store size of blocks of the table top
	private $size;

	function __construct() {
	}

	public function getSize()
	{
		return $this->size;
	}

	public function setSize($size)
	{
		$this->size = $size;
	}
}