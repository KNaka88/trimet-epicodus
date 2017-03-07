<?php

class Leg
{
    private $id;
    private $mode;
    private $start_time;
    private $end_time;
    private $distance;
    private $route_number; // MAX or empty
    private $route_name;
    private $from_id; // Location
    private $to_id; // Location
    private $order; // start, transfer, thru-route, end, empty
    private $stop_sequence; // number of stops
    private $itinerary_id;
    private $leg_number;

    function __construct($mode, $distance, $from,  $to, $itinerary_id, $leg_number, $start_time = null, $end_time = null, $order = null, $route_number = null, $route_name = null, $stop_sequence = 0, $id = null)
    {
        $this->id = $id;
        $this->itinerary_id = $itinerary_id;
        $this->leg_number = $leg_number;
        $this->mode = $mode;
        $this->distance = $distance;
        $this->from_id = $from;
        $this->to_id = $to;
        $this->start_time = $start_time;
        $this->end_time = $end_time;
        $this->order = $order;
        $this->route_number = $route_number;
        $this->route_name = $route_name;
        $this->stop_sequence = $stop_sequence;
    }

    function getId()
    {
        return $this->id;
    }

    function getItineraryId()
    {
        return $this->itinerary_id;
    }

    function getLegNumber()
    {
        return $this->leg_number;
    }

    function getMode()
    {
        return $this->mode;
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

    function getOrder()
    {
        return $this->order;
    }

    function getRouteNumber()
    {
        return $this->route_number;
    }

    function getRouteName()
    {
        return $this->route_name;
    }

    function getStopSequence()
    {
        return $this->stop_sequence;
    }

    function getFromId()
    {
        return $this->from_id;
    }

    function getToId()
    {
        return $this->to_id;
    }

    function setItineraryId($new_value)
    {
        $this->itinerary_id = $new_value;
    }

    function setLegNumber($new_value)
    {
        $this->leg_number = $new_value;
    }

    function setMode($new_value)
    {
        $this->mode = $new_value;
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

    function setOrder($new_value)
    {
        $this->order = $new_value;
    }

    function setRouteNumber($new_value)
    {
        $this->route_number = $new_value;
    }

    function setRouteName($new_value)
    {
        $this->route_name = $new_value;
    }

    function setStopSequence($new_value)
    {
        $this->stop_sequence = $new_value;
    }

    function setFromId($new_value)
    {
        $this->from_id = $new_value;
    }

    function setToId($new_value)
    {
        $this->to_id = $new_value;
    }

    function save()
    {
        $route_name = filter_var($this->getRouteName(), FILTER_SANITIZE_MAGIC_QUOTES);

        $GLOBALS['DB']->exec(
        "INSERT INTO legs (from_id, to_id, itinerary_id, leg_number, distance, stop_sequence, start_time, end_time, route_number, route_name, mode, `order`) VALUES ({$this->getFromId()}, {$this->getToId()}, {$this->getItineraryId()}, {$this->getLegNumber()}, {$this->getDistance()}, {$this->getStopSequence()}, '{$this->getStartTime()}', '{$this->getEndTime()}', '{$this->getRouteNumber()}', '{$route_name}', '{$this->getMode()}', '{$this->getOrder()}');"
        );
        $this->id = $GLOBALS['DB']->lastInsertId();
    }

    static function getAll()
    {
        $query = $GLOBALS['DB']->query("SELECT * FROM legs;");
        return $query->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Leg', ['mode', 'distance', 'from_id',  'to_id', 'itinerary_id', 'leg_number', 'start_time' , 'end_time', 'order' , 'route_number' , 'route_name' , 'stop_sequence', 'id' ]);
    }

    static function deleteAll()
    {
        $GLOBALS['DB']->exec("DELETE FROM legs;");
    }

    static function find($search_id)
    {
        $query = $GLOBALS['DB']->query("SELECT * FROM legs WHERE id = {$search_id};");
        $query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Leg', ['mode', 'distance', 'from_id',  'to_id', 'itinerary_id', 'leg_number',  'start_time' , 'end_time', 'order' , 'route_number' , 'route_name' , 'stop_sequence', 'id']);
        return $query->fetch();
    }
}
?>
