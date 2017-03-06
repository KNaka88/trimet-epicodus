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
}
?>
