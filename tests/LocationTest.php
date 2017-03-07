<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once 'src/Location.php';


    $server = 'mysql:host=localhost:8889;dbname=trimet_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class LocationTest extends PHPUnit_Framework_TestCase
    {
        protected function teardown()
        {
            Location::deleteAll();
        }

        function test_getters()
        {
            //Arrange
            $test_Location = new Location(45.58757, -122.5931, "Portland Int'l Airport MAX Station", 123, 1);

            //Act
            $result = $test_Location->getLatitude().
                      $test_Location->getLongitude().
                      $test_Location->getDescription().
                      $test_Location->getStopId().
                      $test_Location->getId();

            //assert
            $this->assertEquals("45.58757-122.5931Portland Int'l Airport MAX Station1231", $result);

        }

        function test_save()
        {
        //Arrange
        $lat = 558.75700;
        $lon = -122.59310;
        $desc = "Portland Intl Airport MAX Station";
        $stop_id = 123;
        $test_Location = new Location($lat, $lon, $desc, $stop_id);

        $test_Location->save();

        //Act
        $result = Location::getAll();

        //Assert
        $this->assertEquals($test_Location, $result[0]);
        }

        function test_getAll()
        {
            $lat = 558.75700;
            $lon = 122.59310;
            $desc = "Portland Intl Airport MAX Station";
            $stop_id = 123;
            $id = 1;
            $test_Location = new Location($lat, $lon, $desc, $stop_id, $id);
            $test_Location->save();

            $lat2 = 455.19125;
            $lon2 = -226.78982;
            $desc2 = "Pioneer Square North MAX Station";
            $stop_id2 = 456;
            $id2 = 2;
            $test_Location2 = new Location($lat2, $lon2, $desc2, $stop_id2, $id2);
            $test_Location2->save();

            //Act
            $result= Location::getAll();

            //Assert
            $this->assertEquals([$test_Location, $test_Location2], $result);
        }

        function test_deleteAll()
        {
            $lat = 558.5700;
            $lon = -122.59310;
            $desc = "Portland Intl Airport MAX Station";
            $stop_id = 123;
            $id = 1;
            $test_Location = new Location($lat, $lon, $desc, $stop_id, $id);
            $test_Location->save();
            $lat2 = 455.19125;
            $lon2 = -122.67898;
            $desc2 = "Pioneer Square North MAX Station";
            $stop_id2 = 456;
            $id2 = 2;
            $test_Location2 = new Location($lat2, $lon2, $desc2, $stop_id2, $id2);
            $test_Location2->save();
            Location::deleteAll();
            $result= Location::getAll();
            $this->assertEquals([], $result);
        }
        function test_find()
        {
            $lat = 558.5700;
            $lon = -122.59310;
            $desc = "Portland Intl Airport MAX Station";
            $stop_id = 123;
            $id = 1;
            $test_Location = new Location($lat, $lon, $desc, $stop_id, $id);
            $test_Location->save();
            $lat2 = 455.19125;
            $lon2 = -122.67898;
            $desc2 = "Pioneer Square North MAX Station";
            $stop_id2 = 456;
            $id2 = 2;
            $test_Location2 = new Location($lat2, $lon2, $desc2, $stop_id2, $id2);
            $test_Location2->save();

            $result= Location::find($test_Location->getId());

            $this->assertEquals($test_Location, $result);
        }
    }
