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
     * Class constructor creates an empty IGCParser object
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
        return $instance->createFromFile($file_path);


    }

    protected function createFromFile($file_path) {

        $records = array();

        $handle = @fopen($file_path, "r");
        if ($handle) {
            while (($line = fgets($handle)) !== FALSE) {
                $records[] = IGCParser::parseRecord(rtrim($line));
            }
            return new IGCObject($records);
        }
    }


    public static function fromString($string) {

        $instance = new self();
        return $instance->createFromString($string);

    }

    protected function createFromString($igc_string) {

        if(isset($string)) {
            $records = array();

            foreach(preg_split("/((\r?\n)|(\r\n?))/", $igc_string) as $line) {
                $records[] = IGCParser::parseRecord(rtrim($line));
            }

            return new IGCObject($records);
        }

    }

    /**
     * Returns an IGC record object
     *
     * @param        string $string is the raw record line from an IGC file
     * @return       \IGCParser\Record\IGC_Record      Returns the specific IGC_Record object or false if the record isn't supported.
     */
    public static function parseRecord($string)
    {

        $classname = '\\' . __NAMESPACE__ . '\Record\IGC_' . strtoupper(substr($string, 0, 1)) . '_Record';
        return new $classname($string);
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

