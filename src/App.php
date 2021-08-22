<?php 
namespace ToyRobot;

use ToyRobot\Exceptions\InvalidCommandException;
use ToyRobot\Exceptions\RobotNotPlacedException;

/**
 * Main Application class  
 */
class App
{
	private $tableTop;
	private $robot;
	private $command;
	private $hasRobot = false;

	function __construct($tableTop, $robot)
	{
		// dependecy injection both table top and robot instances
		$this->tableTop = $tableTop;
		$this->robot = $robot;
	}

	public function parseCommand($command) {
		// sanitize the command
		$command = filter_var($command, FILTER_SANITIZE_STRING);
		$command = trim($command);
		$command = strtoupper($command);

		// set command to sanitized string
		$this->command = $command;

		// output variable if parseCommand returns anything
		$output = "";

		// parse the provided command, throw exception if invalid command
		$parts = explode(" ", $this->command);
		if (count($parts) > 1) {
			if ($parts[0] != 'PLACE') {
				throw new InvalidCommandException($this->command);
			}
			// using regex to validate PLACE command and pick out arguments for the command
			if (!preg_match('/([0-9]),([0-9]),(NORTH|EAST|WEST|SOUTH)$/', $parts[1], $cordinates)) {
				throw new InvalidCommandException($this->command);
			}
			// arguments are now sotred in cordinates variable
			$this->executePlaceCommand($cordinates);
		} else {
			// check if robot is placed on table top or not
			if (!$this->hasRobot) {
				throw new RobotNotPlacedException();
			}
			switch ($parts[0]) {
				case 'MOVE':
					$this->executeMoveCommand();
					break;
				case 'LEFT':
					$this->executeRotateCommand('LEFT');
					break;
				case 'RIGHT':
					$this->executeRotateCommand('RIGHT');
					break;
				case 'REPORT':
					// prepare the output for report command
					$output = $this->robot->getX() . "," . $this->robot->getY() . "," . $this->robot->getFace();
					break;
				default:
					// non of the commands were match so throw invalid command exception
					throw new InvalidCommandException($this->command);
					break;
			}
		}

		return $output;
	}

	// returns current command
	public function getCommand() {
		return $this->command;
	}

	// execute place command
	private function executePlaceCommand($cordinates) {
		// sanitize the input
		$x = (int)$cordinates[1];
		$y = (int)$cordinates[2];
		$faceString = (string)$cordinates[3];

		// check if robot in placed within the table top
		if ($x >= $this->tableTop->getSize()) {
			throw new InvalidCommandException($this->command);
		}

		// check if robot in placed within the table top
		if ($y >= $this->tableTop->getSize()) {
			throw new InvalidCommandException($this->command);
		}

		$face = null;
		// convert face string to robot const variables
		switch ($faceString) {
			case 'NORTH':
				$face = Robot::FACE_NORTH;
				break;
			case 'SOUTH':
				$face = Robot::FACE_SOUTH;
				break;
			case 'EAST':
				$face = Robot::FACE_EAST;
				break;
			case 'WEST':
				$face = Robot::FACE_WEST;
				break;
		}

		// place command was valid so we can now place the robot on table top
		$this->hasRobot = true;

		// set robots cordinates and face
		$this->robot->setX($x);
		$this->robot->setY($y);
		$this->robot->setFace($faceString);
	}

	private function executeMoveCommand() {
		// get cordinates of the robot
		$x = $this->robot->getX();
		$y = $this->robot->getY();

		// Checking if we move as per the user command then 
		// robot will fall of the table or not
		if ($this->robot->getFace() == Robot::FACE_NORTH) {
			$y++;
		}
		if ($this->robot->getFace() == Robot::FACE_SOUTH) {
			$y--;
		}
		if ($this->robot->getFace() == Robot::FACE_WEST) {
			$x--;
		}
		if ($this->robot->getFace() == Robot::FACE_EAST) {
			$x++;
		}

		// if any of the above moves were invalid then 
		// x axis or y axis will be less than 0 
		// or they will be greater than table top size
		// subtracting 1 from getSize because command cordinates are 0 indexed
		if (
			$x < 0 
			|| $x > ($this->tableTop->getSize() - 1)
			|| $y < 0
			|| $y > ($this->tableTop->getSize() - 1)
		) {
			// if invalid throw exception
			throw new InvalidCommandException($this->command);
		}

		// update the robot cordinates if valid
		$this->robot->setX($x);
		$this->robot->setY($y);
	}

	public function executeRotateCommand($direction) {
		// get the current face of robot
		$currentFace = $this->robot->getFace();
		// variable to store face
		$face = null;

		// LEFT and RIGHT will rotate the robot 90 degrees in the specified direction without changing the position of the robot.
		if ($currentFace == Robot::FACE_NORTH) {
			$face = ($direction == 'LEFT') ? Robot::FACE_WEST : Robot::FACE_EAST;
		}
		if ($currentFace == Robot::FACE_SOUTH) {
			$face = ($direction == 'LEFT') ? Robot::FACE_EAST : Robot::FACE_WEST;
		}
		if ($currentFace == Robot::FACE_WEST) {
			$face = ($direction == 'LEFT') ? Robot::FACE_SOUTH : Robot::FACE_NORTH;
		}
		if ($currentFace == Robot::FACE_EAST) {
			$face = ($direction == 'LEFT') ? Robot::FACE_NORTH : Robot::FACE_SOUTH;
		}

		//setting the face of the robot
		$this->robot->setFace($face);
	}
}