<?php
/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

require_once 'src/Leg.php';

$server = 'mysql:host=localhost:8889;dbname=trimet_test';
$username = 'root';
$password = 'root';
$DB = new PDO($server, $username, $password);

class LegTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        Leg::deleteAll();
    }

    function test_save()
    {
        //Arrange
        $mode = "Light Rail";
        $start_date_time = new DateTime(); // current date
        $start_date_time->setTime(8, 42); // today at 08:42
        $end_date_time = new DateTime(); // current date
        $end_date_time->setTime(10, 0); // today at 10:00
        $start_time = $start_date_time->format('Y-m-d H:i:s');
        $end_time = $end_date_time->format('Y-m-d H:i:s');
        $distance = 13.33;
        $route_number = "MAX"; // MAX or empty
        $route_name = "MAX Red Line to City Center & Beaverton";
        $from_id = 1; // Location
        $to_id = 2; // Location
        $order = "start"; // start, transfer, thru-route, end, empty
        $stop_sequence = 20; // number of stops
        $test_Leg = new Leg($mode, $distance, $from_id, $to_id, $start_time, $end_time, $order, $route_number, $route_name, $stop_sequence);

        //Act
        $test_Leg->save();
        $result = Leg::getAll();

        //Assert
        $this->assertEquals([$test_Leg], $result);
    }

    function test_getAll()
    {
        //Arrange
        $mode = "Light Rail";
        $start_date_time = new DateTime(); // current date
        $start_date_time->setTime(8, 42); // today at 08:42
        $end_date_time = new DateTime(); // current date
        $end_date_time->setTime(10, 0); // today at 10:00
        $start_time = $start_date_time->format('Y-m-d H:i:s');
        $end_time = $end_date_time->format('Y-m-d H:i:s');
        $distance = 13.33;
        $route_number = "MAX"; // MAX or empty
        $route_name = "MAX Red Line to City Center & Beaverton";
        $from_id = 1; // Location
        $to_id = 2; // Location
        $order = "start"; // start, transfer, thru-route, end, empty
        $stop_sequence = 20; // number of stops
        $test_Leg = new Leg($mode, $distance, $from_id, $to_id, $start_time, $end_time, $order, $route_number, $route_name, $stop_sequence);
        $test_Leg->save();

        $mode2 = "Light Rail";
        $start_date_time2 = new DateTime(); // current date
        $start_date_time->setTime(10, 42); // today at 08:42
        $end_date_time2 = new DateTime(); // current date
        $end_date_time->setTime(11, 0); // today at 10:00
        $start_time2 = $start_date_time->format('Y-m-d H:i:s');
        $end_time2 = $end_date_time->format('Y-m-d H:i:s');
        $distance2 = 16.22;
        $route_number2 = "MAX"; // MAX or empty
        $route_name2 = "MAX Red Line to PDX";
        $from_id2 = 3; // Location
        $to_id2 = 4; // Location
        $order2 = "transfer"; // start, transfer, thru-route, end, empty
        $stop_sequence2 = 20; // number of stops
        $test_Leg2 = new Leg($mode2, $distance2, $from_id2, $to_id2, $start_time2, $end_time2, $order2, $route_number2, $route_name2, $stop_sequence2);
        $test_Leg2->save();

        //Act
        $result = Leg::getAll();

        //Assert
        $this->assertEquals([$test_Leg, $test_Leg2], $result);
    }

    function test_deleteAll()
    {
        //Arrange
        $mode = "Light Rail";
        $start_date_time = new DateTime(); // current date
        $start_date_time->setTime(8, 42); // today at 08:42
        $end_date_time = new DateTime(); // current date
        $end_date_time->setTime(10, 0); // today at 10:00
        $start_time = $start_date_time->format('Y-m-d H:i:s');
        $end_time = $end_date_time->format('Y-m-d H:i:s');
        $distance = 13.33;
        $route_number = "MAX"; // MAX or empty
        $route_name = "MAX Red Line to City Center & Beaverton";
        $from_id = 1; // Location
        $to_id = 2; // Location
        $order = "start"; // start, transfer, thru-route, end, empty
        $stop_sequence = 20; // number of stops
        $test_Leg = new Leg($mode, $distance, $from_id, $to_id, $start_time, $end_time, $order, $route_number, $route_name, $stop_sequence);
        $test_Leg->save();

        $mode2 = "Light Rail";
        $start_date_time2 = new DateTime(); // current date
        $start_date_time->setTime(10, 42); // today at 08:42
        $end_date_time2 = new DateTime(); // current date
        $end_date_time->setTime(11, 0); // today at 10:00
        $start_time2 = $start_date_time->format('Y-m-d H:i:s');
        $end_time2 = $end_date_time->format('Y-m-d H:i:s');
        $distance2 = 16.22;
        $route_number2 = "MAX"; // MAX or empty
        $route_name2 = "MAX Red Line to PDX";
        $from_id2 = 3; // Location
        $to_id2 = 4; // Location
        $order2 = "transfer"; // start, transfer, thru-route, end, empty
        $stop_sequence2 = 20; // number of stops
        $test_Leg2 = new Leg($mode2, $distance2, $from_id2, $to_id2, $start_time2, $end_time2, $order2, $route_number2, $route_name2, $stop_sequence2);
        $test_Leg2->save();

        //Act
        Leg::deleteAll();
        $result = Leg::getAll();


        //Assert
        $this->assertEquals([], $result);
    }

    function test_find()
    {
        //Arrange
        $mode = "Light Rail";
        $start_date_time = new DateTime(); // current date
        $start_date_time->setTime(8, 42); // today at 08:42
        $end_date_time = new DateTime(); // current date
        $end_date_time->setTime(10, 0); // today at 10:00
        $start_time = $start_date_time->format('Y-m-d H:i:s');
        $end_time = $end_date_time->format('Y-m-d H:i:s');
        $distance = 13.33;
        $route_number = "MAX"; // MAX or empty
        $route_name = "MAX Red Line to City Center & Beaverton";
        $from_id = 1; // Location
        $to_id = 2; // Location
        $order = "start"; // start, transfer, thru-route, end, empty
        $stop_sequence = 20; // number of stops
        $test_Leg = new Leg($mode, $distance, $from_id, $to_id, $start_time, $end_time, $order, $route_number, $route_name, $stop_sequence);
        $test_Leg->save();

        $mode2 = "Light Rail";
        $start_date_time2 = new DateTime(); // current date
        $start_date_time->setTime(10, 42); // today at 08:42
        $end_date_time2 = new DateTime(); // current date
        $end_date_time->setTime(11, 0); // today at 10:00
        $start_time2 = $start_date_time->format('Y-m-d H:i:s');
        $end_time2 = $end_date_time->format('Y-m-d H:i:s');
        $distance2 = 16.22;
        $route_number2 = "MAX"; // MAX or empty
        $route_name2 = "MAX Red Line to PDX";
        $from_id2 = 3; // Location
        $to_id2 = 4; // Location
        $order2 = "transfer"; // start, transfer, thru-route, end, empty
        $stop_sequence2 = 20; // number of stops
        $test_Leg2 = new Leg($mode2, $distance2, $from_id2, $to_id2, $start_time2, $end_time2, $order2, $route_number2, $route_name2, $stop_sequence2);
        $test_Leg2->save();
        $search_id = $test_Leg2->getId();

        //Act
        $result = Leg::find($search_id);

        //Assert
        $this->assertEquals($test_Leg2, $result);
    }
}
?>
