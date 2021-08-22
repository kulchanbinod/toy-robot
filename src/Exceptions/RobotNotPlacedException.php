<?php 
namespace ToyRobot\Exceptions;

/**
 * InvalidCommandException.php
 */
class RobotNotPlacedException extends \Exception
{
	public function __construct($message = "Robot is not placed", $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}