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
}
?>
