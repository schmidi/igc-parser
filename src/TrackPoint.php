<?php

namespace IGCParser;


class TrackPoint
{

    public $longitude;

    public $latitude;

    public $altitude;

    public $timestamp;

    /**
     * TrackPoint constructor.
     * @param $longitude
     * @param $latitude
     * @param $altitude
     * @param $timestamp
     */
    public function __construct($longitude, $latitude, $altitude, $timestamp)
    {
        $this->longitude = $longitude;
        $this->latitude = $latitude;
        $this->altitude = $altitude;
        $this->timestamp = $timestamp;
    }


}