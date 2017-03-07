<?php
/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

require_once 'src/Itinerary.php';

$server = 'mysql:host=localhost:8889;dbname=trimet_test';
$username = 'root';
$password = 'root';
$DB = new PDO($server, $username, $password);

class ItineraryTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        Itinerary::deleteAll();
        Leg::deleteAll();
    }

    function test_save()
    {
        //Arrange
        $distance = 10.10;
        $start_date_time = new DateTime();
        $start_date_time->setTime(16, 12);
        $start_time = $start_date_time->format('Y-m-d H:i:s');
        $end_date_time = new DateTime();
        $end_date_time->setTime(15, 19);
        $end_time = $end_date_time->format('Y-m-d H:i:s');
        $test_itinerary = new Itinerary($distance, $start_time, $end_time);

        //Act
        $test_itinerary->save();
        $result = Itinerary::getAll();

        //Assert
        $this->assertEquals([$test_itinerary], $result);
    }

    function test_getAll()
    {
        //Arrange
        $distance = 10.10;
        $start_date_time = new DateTime();
        $start_date_time->setTime(16, 12);
        $start_time = $start_date_time->format('Y-m-d H:i:s');
        $end_date_time = new DateTime();
        $end_date_time->setTime(15, 19);
        $end_time = $end_date_time->format('Y-m-d H:i:s');
        $test_itinerary = new Itinerary($distance, $start_time, $end_time);
        $test_itinerary->save();

        $distance2 = 10.10;
        $start_date_time2 = new DateTime();
        $start_date_time2->setTime(16, 12);
        $start_time2 = $start_date_time2->format('Y-m-d H:i:s');
        $end_date_time2 = new DateTime();
        $end_date_time2->setTime(15, 19);
        $end_time2 = $end_date_time2->format('Y-m-d H:i:s');
        $test_itinerary2 = new Itinerary($distance2, $start_time2, $end_time2);
        $test_itinerary2->save();

        //Act
        $result = Itinerary::getAll();

        //Assert
        $this->assertEquals([$test_itinerary, $test_itinerary2], $result);
    }

    function test_deleteAll()
    {
        //Arrange
        $distance = 10.10;
        $start_date_time = new DateTime();
        $start_date_time->setTime(16, 12);
        $start_time = $start_date_time->format('Y-m-d H:i:s');
        $end_date_time = new DateTime();
        $end_date_time->setTime(15, 19);
        $end_time = $end_date_time->format('Y-m-d H:i:s');
        $test_itinerary = new Itinerary($distance, $start_time, $end_time);
        $test_itinerary->save();

        $distance2 = 10.59;
        $start_date_time2 = new DateTime();
        $start_date_time2->setTime(16, 12);
        $start_time2 = $start_date_time2->format('Y-m-d H:i:s');
        $end_date_time2 = new DateTime();
        $end_date_time2->setTime(15, 19);
        $end_time2 = $end_date_time2->format('Y-m-d H:i:s');
        $test_itinerary2 = new Itinerary($distance2, $start_time2, $end_time2);
        $test_itinerary2->save();

        //Act
        Itinerary::deleteAll();
        $result = Itinerary::getAll();

        //Assert
        $this->assertEquals([], $result);
    }

    function test_find()
    {
        //Arrange
        $distance = 10.10;
        $start_date_time = new DateTime();
        $start_date_time->setTime(16, 12);
        $start_time = $start_date_time->format('Y-m-d H:i:s');
        $end_date_time = new DateTime();
        $end_date_time->setTime(15, 19);
        $end_time = $end_date_time->format('Y-m-d H:i:s');
        $test_itinerary = new Itinerary($distance, $start_time, $end_time);
        $test_itinerary->save();

        $distance2 = 10.40;
        $start_date_time2 = new DateTime();
        $start_date_time2->setTime(16, 12);
        $start_time2 = $start_date_time2->format('Y-m-d H:i:s');
        $end_date_time2 = new DateTime();
        $end_date_time2->setTime(15, 19);
        $end_time2 = $end_date_time2->format('Y-m-d H:i:s');
        $test_itinerary2 = new Itinerary($distance2, $start_time2, $end_time2);
        $test_itinerary2->save();
        $search_id = $test_itinerary2->getId();

        //Act
        $result = Itinerary::find($search_id);

        //Assert
        $this->assertEquals($test_itinerary2, $result);
    }

    function test_getLegs()
    {
        //Arrange
        $distance = 10.10;
        $start_date_time = new DateTime();
        $start_date_time->setTime(16, 12);
        $start_time = $start_date_time->format('Y-m-d H:i:s');
        $end_date_time = new DateTime();
        $end_date_time->setTime(15, 19);
        $end_time = $end_date_time->format('Y-m-d H:i:s');
        $test_itinerary = new Itinerary($distance, $start_time, $end_time);
        $test_itinerary->save();
        $itinerary_id = $test_itinerary->getId();


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
        $leg_number = 0;
        $test_Leg = new Leg($mode, $distance, $from_id, $to_id, $itinerary_id, $leg_number, $start_time, $end_time, $order, $route_number, $route_name, $stop_sequence);

        $mode2 = "Walk";
        $start_date_time2 = new DateTime();
        $start_date_time->setTime(10, 0);
        $end_date_time2 = new DateTime();
        $end_date_time->setTime(10, 1);
        $start_time2 = $start_date_time->format('Y-m-d H:i:s');
        $end_time2 = $end_date_time->format('Y-m-d H:i:s');
        $distance2 = 0.01;
        // $route_number2 = null; // MAX or empty
        // $route_name2 = null;
        $from_id2 = 2; // Location
        $to_id2 = 3; // Location
        // $order2 = null; // start, transfer, thru-route, end, empty
        // $stop_sequence2 = null; // number of stops
        $leg_number2 = 1;
        $test_Leg2 = new Leg($mode2, $distance2, $from_id2, $to_id2, $itinerary_id, $leg_number2, $start_time2, $end_time2);

        $mode3 = "Light Rail";
        $start_date_time3 = new DateTime();
        $start_date_time->setTime(10, 5);
        $end_date_time3 = new DateTime();
        $end_date_time->setTime(11, 0);
        $start_time3 = $start_date_time->format('Y-m-d H:i:s');
        $end_time3 = $end_date_time->format('Y-m-d H:i:s');
        $distance3 = 11;
        $route_number3 = 'MAX'; // MAX or empty
        $route_name3 = 'MAX Green Line to Clackamas Town Center';
        $from_id3 = 3; // Location
        $to_id3 = 4; // Location
        $order3 = 'end'; // start, transfer, thru-route, end, empty
        $stop_sequence3 = 5; // number of stops
        $leg_number3 = 2;
        $test_Leg3 = new Leg($mode3, $distance3, $from_id3, $to_id3, $itinerary_id, $leg_number3, $start_time3, $end_time3, $order3, $route_number3, $route_name3, $stop_sequence3);


        $test_Leg->save();
        $test_Leg2->save();
        $test_Leg3->save();


        //Act
        $result = $test_itinerary->getLegs();

        //Assert
        $this->assertEquals([$test_Leg, $test_Leg2, $test_Leg3], $result);
    }
}
?>
