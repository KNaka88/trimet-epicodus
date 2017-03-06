<?php
class Itinerary
{
    private $id;
    private $distance;
    private $start_time;
    private $end_time;

    function __construct($distance, $start_time, $end_time, $id = null)
    {
        $this->id = $id;
        $this->distance = $distance;
        $this->start_time = $start_time;
        $this->end_time = $end_time;
    }

    function getId()
    {
        return $this->id;
    }

    function getDistance()
    {
        return $this->distance;
    }

    function getStartTime()
    {
        return $this->start_time;
    }

    function getEndTime()
    {
        return $this->end_time;
    }

    function setDistance($new_value)
    {
        $this->distance = $new_value;
    }

    function setStartTime($new_value)
    {
        $this->start_time = $new_value;
    }

    function setEndTime($new_value)
    {
        $this->end_time = $new_value;
    }

    function save()
    {
        $GLOBALS['DB']->exec("INSERT INTO itineraries (distance, start_time, end_time) VALUES ({$this->getDistance()}, '{$this->getStartTime()}', '{$this->getEndTime()}');");
        $this->id = $GLOBALS['DB']->lastInsertId();
    }

    static function getAll()
    {
        $query = $GLOBALS['DB']->query("SELECT * FROM itineraries;");
        return $query->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Itinerary', [ 'distance', 'start_time', 'end_time', 'id' ]);
    }

    static function deleteAll()
    {
        $GLOBALS['DB']->exec("DELETE FROM itineraries;");
    }

    static function find($search_id)
    {
        $query = $GLOBALS['DB']->query("SELECT * FROM itineraries WHERE id = {$search_id};");
        $query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Itinerary', [ 'distance', 'start_time', 'end_time', 'id' ]);
        return $query->fetch();
    }
}
?>
