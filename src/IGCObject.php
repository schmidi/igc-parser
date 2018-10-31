<?php

namespace IGCParser;


use IGCParser\Record\IGC_Record;

class IGCObject
{

    /**
     * The date and time of the flight
     * @access public
     * @var \DateTime
     */
    public $datetime;

    /**
     * The Pilot's name
     * @access public
     * @var string
     */
    public $pilot;

    /**
     * The Glider type
     * @access public
     * @var string
     */
    public $glider_type;

    /**
     * The Glider ID
     * @access public
     * @var string
     */
    public $glider_id;

    /**
     * The max altitude of the flight
     * @access public
     * @var string
     */
    public $max_altitude;

    /**
     * The minimum altitude of the flight
     * @access public
     * @var string
     */
    public $min_altitude;

    /**
     * The total distance of the flight
     * @access public
     * @var string
     */
    public $distance;

    /**
     * The total duration of the flight (in seconds)
     * @access public
     * @var integer
     */
    public $duration;

    private $records = array();

    /**
     * IGCObject constructor.
     * @param array $records
     * @throws \InvalidArgumentException
     */
    public function __construct(array $records)
    {

        if($records == 0) {
            throw new \InvalidArgumentException("No igc records found, cannot create object.");
        }

        foreach ($records as $record) {
            if(!($record instanceof IGC_Record)) {
                throw new \InvalidArgumentException("Invalid record found, cannot create object.");
            }
        }

        $this->records = $records;
        $this->initializeProperties();

    }

    /**
     * Sets the details of the IGC files from the record objects within
     */
    private function initializeProperties()
    {
        $this->max_altitude = 0;
        $this->min_altitude = 80000;

        // set lowest and highest altitude
        if (is_array($this->records)) {
            $this->datetime = new \DateTime("1970-01-01");
            $start_found = false;

            foreach ($this->records as $each) {
                if ($each->type == 'H') {
                    if ($each->tlc == 'DTE') {
                        $this->datetime->setDate('20' . substr($each->value, 4, 2),
                            substr($each->value, 2, 2),
                            substr($each->value, 0, 2));
                    } elseif ($each->tlc == 'PLT') {
                        $this->pilot = ucwords(strtolower(trim($each->value)));
                    } elseif ($each->tlc == 'GTY') {
                        $this->glider_type = ucwords(strtolower(trim($each->value)));
                    }
                } elseif ($each->type == 'B') {
                    $record_time = clone $this->datetime;
                    $record_time->setTime($each->time_array['h'],
                        $each->time_array['m'],
                        $each->time_array['s']);
                    if (!$start_found) {
                        $start_found = true;
                        $this->datetime = $record_time;
                    }
                    $this->duration = $record_time->getTimestamp() - $this->datetime->getTimestamp();
                    if ($each->pressure_altitude > $this->max_altitude) {
                        $this->max_altitude = $each->pressure_altitude;
                    } elseif ($each->pressure_altitude < $this->min_altitude) {
                        $this->min_altitude = $each->pressure_altitude;
                    }
                }
            }
        }

        // reset to 0 if a minimum altitude was never recorded
        if ($this->min_altitude == 80000) {
            $this->min_altitude = 0;
        }
    }

    /**
     * @return array of track points
     */
    public function getTrackPoints()
    {

        $trackPoints = array();

        foreach ($this->records as $each) {

            if ($each->type == "B") {

                array_push($trackPoints, array("lat" => $each->latitude['decimal_degrees'], "long" => $each->longitude['decimal_degrees']));

            }
        }

        return $trackPoints;
    }


}