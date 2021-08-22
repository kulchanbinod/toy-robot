<?php
use PHPUnit\Framework\TestCase;

use ToyRobot\App;
use ToyRobot\TableTop;
use ToyRobot\Robot;
use ToyRobot\Exceptions\InvalidCommandException;
use ToyRobot\Exceptions\RobotNotPlacedException;;

final class RobotTest extends TestCase
{
    private $app;
    private $tableTop;
    private $robot;

    protected function setUp(): void
    {
        // setting up our application to run the test cases
        $this->tableTop = new TableTop();
        $this->tableTop->setSize(5);
        $this->robot = new Robot();
        $this->app = new App($this->tableTop,$this->robot);
    }

    public function test_robot_can_not_move_before_place()
    {
        $this->expectException(RobotNotPlacedException::class);

        $this->app->parseCommand("left");
    }

    public function test_check_for_invalid_place_command()
    {
        $this->expectException(InvalidCommandException::class);

        $this->app->parseCommand("south 1,3,foo");
    }

    public function test_check_for_invalid_move_command()
    {
        $this->expectException(InvalidCommandException::class);

        $this->app->parseCommand("place 0,0,North");
        $this->app->parseCommand("up");
    }

    public function test_can_place_robot_on_board()
    {
        $this->app->parseCommand("place 1,1,North");

        $output = $this->app->parseCommand("report");

        $this->assertEquals("1,1,NORTH", $output);
    }

    public function test_can_robot_move()
    {
        $this->app->parseCommand("place 0,0,North");

        $this->app->parseCommand("move");
        $this->app->parseCommand("move");

        $output = $this->app->parseCommand("report");

        $this->assertEquals("0,2,NORTH", $output);
    }

    public function test_can_robot_rotate()
    {
        $this->app->parseCommand("place 0,0,North");

        $this->app->parseCommand("move");
        $this->app->parseCommand("right");
        $this->app->parseCommand("move");

        $output = $this->app->parseCommand("report");
        $this->assertEquals("1,1,EAST", $output);

        $this->app->parseCommand("right");
        $output = $this->app->parseCommand("report");

        $this->assertEquals("1,1,SOUTH", $output);
    }

    public function test_robot_can_not_move_invalid_command_1()
    {
        $this->expectException(InvalidCommandException::class);

        $this->app->parseCommand("place 0,0,North");

        $this->app->parseCommand("left");
        $this->app->parseCommand("move");
    }

    public function test_robot_can_not_move_invalid_command_2()
    {
        $this->expectException(InvalidCommandException::class);

        $this->app->parseCommand("place 0,0,SOUTH");

        $this->app->parseCommand("move");
    }
}
