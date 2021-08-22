<?php 
namespace ToyRobot;
/**
 * Robot Class
 */
class Robot
{
	// const for storing robot face
	const FACE_NORTH = 'NORTH';
	const FACE_SOUTH = 'SOUTH';
	const FACE_EAST = 'EAST';
	const FACE_WEST = 'WEST';

	// robot x cordinates
	private $x;
	// robot y cordinates
	private $y;
	// robot current face
	private $face;

	public function setX($x) {
		$this->x = $x;
	}

	public function setY($y) {
		$this->y = $y;
	}

	public function getX() {
		return $this->x;
	}

	public function getY() {
		return $this->y;
	}

	public function setFace($face) {
		$this->face = $face;
	}

	public function getFace() {
		return $this->face;
	}
}