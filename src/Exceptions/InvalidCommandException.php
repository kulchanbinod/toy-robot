<?php 
namespace ToyRobot\Exceptions;

/**
 * InvalidCommandException.php
 */
class InvalidCommandException extends \Exception
{
	public function __construct($message, $code = 0, Exception $previous = null) {
		$message = $message . " is not a valid command";
        parent::__construct($message, $code, $previous);
    }
}