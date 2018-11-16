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
    public $start_datetime;

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

    private $start_found = false;

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
            $this->start_datetime = new \DateTime("1970-01-01");

            foreach ($this->records as $current_record) {
                if ($current_record->type == 'H') {
                    $this->processHTypes($current_record);
                } elseif ($current_record->type == 'B') {
                    $this->processBTypes($current_record);
                }
            }
        }

        // reset to 0 if a minimum altitude was never recorded
        if ($this->min_altitude == 80000) {
            $this->min_altitude = 0;
        }
    }

    /**
     * @param int $epsilon optimization factor
     * @return array of track points
     */
    public function getTrackPoints($epsilon = 0)
    {

        $trackPoints = array();

        foreach ($this->records as $current) {

            if ($current->type == "B") {

                $trackPoint = new TrackPoint(
                    $current->latitude['decimal_degrees'],
                    $current->longitude['decimal_degrees'],
                    $current->pressure_altitude);

                array_push($trackPoints, $trackPoint);

            }
        }

        if($epsilon > 0) {
            $trackPoints = RDPLineSimplification::RDPLineSimplification($trackPoints, $epsilon);
        }

        return $trackPoints;
    }

    /**
     * @param $record
     */
    private function processHTypes($record)
    {
        if ($record->tlc == 'DTE') {
            $this->start_datetime->setDate('20' . substr($record->value, 4, 2),
                substr($record->value, 2, 2),
                substr($record->value, 0, 2));
        } elseif ($record->tlc == 'PLT') {
            $this->pilot = ucwords(strtolower(trim($record->value)));
        } elseif ($record->tlc == 'GTY') {
            $this->glider_type = ucwords(strtolower(trim($record->value)));
        }
    }

    /**
     * @param $record
     * @param $start_found
     */
    private function processBTypes($record)
    {
        $record_time = clone $this->start_datetime;
        $record_time->setTime($record->time_array['h'], $record->time_array['m'], $record->time_array['s']);
        if (!$this->start_found) {
            $this->start_found = true;
            $this->start_datetime = $record_time;
        }
        $this->duration = $record_time->getTimestamp() - $this->start_datetime->getTimestamp();
        if ($record->pressure_altitude > $this->max_altitude) {
            $this->max_altitude = $record->pressure_altitude;
        } elseif ($record->pressure_altitude < $this->min_altitude) {
            $this->min_altitude = $record->pressure_altitude;
        }
    }


}