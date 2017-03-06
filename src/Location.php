<?php
class Location
{
    private $id;
    private $latitude;
    private $longitude;
    private $description;

    function __construct($lat, $long, $desc = null, $id = null)
    {
        $this->latitude = $lat;
        $this->longitude = $long;
        $this->description = $desc;
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

    function save()
    {
        $GLOBALS['DB']->exec("INSERT INTO locations (latitude, longitude, description) VALUES ({$this->getLatitude()}, {$this->getLongitude()}, '{$this->getDescription()}');");
        $this->id = $GLOBALS['DB']->lastInsertId();
    }

    static function getAll()
    {
        $query = $GLOBALS['DB']->query("SELECT * FROM locations;");
        return $query->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Location', [ 'latitude', 'longitude', 'description', 'id' ]);
    }

    static function find($search_id)
    {
        $found_location = null;
        $query = $GLOBALS['DB']->query("SELECT * FROM locations WHERE id = {$search_id};");
        $query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Location', [ 'latitude', 'longitude', 'description', 'id' ]);
        $found_location = $query->fetch();
        return $found_location;
    }
}
?>
