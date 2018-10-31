<?php
/**
 * IGC_Record
 *
 * The base record class to be extended by all other record classes.
 *
 * @version 0.1
 * @author Mike Milano <coder1@gmail.com>
 * @project php-igc
 */

namespace IGCParser\Model;

class IGC_Record
{
    /**
     * The single byte record type.
     * @access public
     * @var string
     */
    public $type;

    /**
     * The raw record string from the IGC file
     * @access public
     * @var string
     */
    public $raw;

    /**
     * IGC_Record constructor.
     * @param string $record
     * @param string $type
     */
    public function __construct(string $record, string $type)
    {
        // TODO: strip line ending
        $this->raw = $record;
        $this->type = $type;
    }


}
