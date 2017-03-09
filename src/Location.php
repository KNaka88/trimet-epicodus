<?php
class Location
{
    private $latitude;
    private $longitude;
    private $description;
    private $stop_id;
    private $id;

    function __construct($lat, $long, $desc = " ", $stop_id=null, $id = null)
    {
        $this->latitude = (float) $lat;
        $this->longitude = (float) $long;
        $this->description = (string) $desc;
        $this->stop_id = $stop_id;
        $this->id = $id;
    }

    function getId()
    {
        return $this->id;
    }

    function getLatitude()
    {
        return $this->latitude;
    }

    function getLongitude()
    {
        return $this->longitude;
    }

    function getDescription()
    {
        return $this->description;
    }

    function getStopId()
    {
        return $this->stop_id;
    }

    function save()
    {
        $description = filter_var($this->getDescription(), FILTER_SANITIZE_MAGIC_QUOTES);
        $GLOBALS['DB']->exec("INSERT INTO locations (latitude, longitude, description, stop_id) VALUES ({$this->getLatitude()}, {$this->getLongitude()}, '{$description}', {$this->getStopId()});");

    
        $this->id = $GLOBALS['DB']->lastInsertId();

    }

    static function getAll()
    {
        $returned_locations = $GLOBALS['DB']->query("SELECT * FROM locations;");
        $locations= [];
        foreach ($returned_locations as $location) {
            $latitude= $location['latitude'];
            $longitude= $location['longitude'];
            $description= $location['description'];
            $stop_id = $location['stop_id'];
            $id= $location['id'];
            $new_location= new Location($latitude, $longitude, $description, $stop_id, $id, 100);
            array_push($locations, $new_location);
        }
        return $locations;
        // return $query->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Location', [ 'latitude', 'longitude', 'description', 'stop_id', 'id' ]);
    }
    static function deleteAll()
    {
        $GLOBALS['DB']->exec("DELETE FROM locations;");
    }

    static function find($search_id)
    {
        // $found_location = null;
        // $query = $GLOBALS['DB']->query("SELECT * FROM locations WHERE id = {$search_id};");
        // $query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Location', [ 'latitude', 'longitude', 'description', 'stop_id', 'id' ]);
        // $found_location = $query->fetch();
        // var_dump($found_location);
        // return $found_location;
        $found_location= null;
        $all_locations= Location::getAll();
        foreach ($all_locations as $location) {

            if ($location->getId()==$search_id) {
                $found_location= $location;
            }
        }
        return $found_location;

    }
}
?>
