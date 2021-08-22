<?php 
session_start();

require 'vendor/autoload.php';

if (isset($_GET['clear'])) {
	// clear out all session 
	session_destroy();
	header("Location: /");
}

// new instance of TableTop class
$tableTop = new ToyRobot\TableTop();
// set size to 5 unit
$tableTop->setSize(5);

// new instance of Robot class
$robot = new ToyRobot\Robot();

// new instance of applicaiton class
$application = new ToyRobot\App($tableTop, $robot);

// check if commands exists or not in session
if (!isset($_SESSION['commands'])) {
	$_SESSION['commands'] = [];
}

foreach ($_SESSION['commands'] as $command) {
	// if there are already commands on the session run them first to fix the state of the robot
	try {
		$application->parseCommand($command);
	} catch (Exception $e) {
		
	}
}

// check if users have given command or not
if (isset($_POST['command'])) {
	try {
		// try to parse the command and run it
		// some command like "report" can return output so storing that into a variable
		$output = $application->parseCommand($_POST['command']);

		// pushing the command to the commands list
		$_SESSION['commands'][] = $application->getCommand();

		// if there is some output then push it to the command list for display
		if ($output) {
			$_SESSION['commands'][] = $output;
		}
	} catch (Exception $e) {

	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/style.css">
	<title>ToyRobot</title>
</head>
<body>
	<div class="container">
		<textarea name="screen" readonly="readonly"><?php foreach ($_SESSION['commands'] as $command) {
		// print out all the commands
		echo $command."\n";
		} ?></textarea>
		<form class="input-wrapper" action="" method="post">
			<input type="text" name="command" placeholder="Type your commands here..." required autofocus />
			<button type="submit">Send</button>
		</form>
		<a class="clear" href="/?clear=1">Clear</a>
	</div>
</body>
</html>