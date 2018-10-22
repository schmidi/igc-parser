<?php
/**
 * PHP_IGC
 *
 * This class is instantiated with the file path of the IGC file. It
 * will create an array of IGC record objects for convenient use of the data.
 *
 * @version 0.2
 * @author Mike Milano <coder1@gmail.com>
 * @author Tobias Schmid <schmid.tobias@bluewin.ch>
 * @project php-igc
 */

namespace IGCParser;


class IGCParser
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
     * Class constructor creates an empty PHP_IGC object
     * Instantiate via: $object = IGCParser::fromFile(...) or IGCParser::fromString(...)
     *
     */
    public function __construct()
    {
        // leave blank as long as nothing must
        // be initialized with default values
    }


    public static function fromFile($file_path) {

        $instance = new self();
        $instance->createFromFile($file_path);
        return $instance;

    }

    protected function createFromFile($file_path) {

        $handle = @fopen($file_path, "r");
        if ($handle) {
            while (($line = fgets($handle)) !== FALSE) {
                $this->records[] = IGCParser::parseRecord($line);
            }
            $this->initializeProperties();
        }

    }


    public static function fromString($string) {

        $instance = new self();
        $instance->createFromString($string);
        return $instance;

    }

    protected function createFromString($igc_string) {

        if(isset($string)) {

            foreach(preg_split("/((\r?\n)|(\r\n?))/", $igc_string) as $line) {
                $this->records[] = IGCParser::parseRecord($line);
            }

            $this->initializeProperties();
        }

    }

    /**
     * Returns an IGC record object
     *
     * @param        string $string is the raw record line from an IGC file
     * @return       \IGCParser\Model\IGC_Record      Returns the specific IGC_Record object or false if the record isn't supported.
     */
    public static function parseRecord($string)
    {

        $classname = '\\' . __NAMESPACE__ . '\Model\IGC_' . strtoupper(substr($string, 0, 1)) . '_Record';
        return new $classname($string);
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
                        $this->pilot = ucwords(strtolower($each->value));
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

    public function getTrackPoints()
    {

        if (count($this->records) < 1) {
            throw new \Exception("Invalid File");
        }

        $trackPoints = array();

        foreach ($this->records as $each) {

            if ($each->type == "B") {

                array_push($trackPoints, array("lat" => $each->latitude['decimal_degrees'], "long" => $each->longitude['decimal_degrees']));

            }
        }

        return $trackPoints;
    }

    /**
     * Returns the full manufacturer string from the code defined in the A record
     *
     * @param        string $code is the manufacturer's code from the A record
     * @return       string  Full manufacturer string
     */
    public static function GetManufacturerFromCode($code)
    {
        // manufacturer array
        $man = array();

        $man['B'] = "Borgelt";
        $man['C'] = "Cambridge";
        $man['E'] = "EW";
        $man['F'] = "Filser";
        $man['I'] = "Ilec";
        $man['M'] = "Metron";
        $man['P'] = "Peschges";
        $man['S'] = "Sky Force";
        $man['T'] = "PathTracker";
        $man['V'] = "Varcom";
        $man['W'] = "Westerboer";
        $man['Z'] = "Zander";
        $man['1'] = "Collins";
        $man['2'] = "Honeywell";
        $man['3'] = "King";
        $man['4'] = "Garmin";
        $man['5'] = "Trimble";
        $man['6'] = "Motorola";
        $man['7'] = "Magellan";
        $man['8'] = "Rockwell";

        if (!$man[(string)$code]) {
            return false;
        }

        return $man[(string)$code];
    }


}

