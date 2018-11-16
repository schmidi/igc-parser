<?php

namespace IGCParser;


class TrackPoint
{

    public $longitude;

    public $latitude;

    public $altitude;

    /**
     * TrackPoint constructor.
     * @param $longitude
     * @param $latitude
     * @param $altitude
     */
    public function __construct($longitude, $latitude, $altitude)
    {
        $this->longitude = $longitude;
        $this->latitude = $latitude;
        $this->altitude = $altitude;
    }


}