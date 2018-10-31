<?php

declare(strict_types=1);

use IGCParser\IGCParser;
use PHPUnit\Framework\TestCase;


final class IGCRecordTest extends TestCase {


    public function testARecord() {

        $aRecord = new \IGCParser\Record\IGC_A_Record("AXSX001 SKYTRAXX V1.89 SN:2956932040");

        $this->assertNotNull($aRecord);
        $this->assertEquals("AXSX001 SKYTRAXX V1.89 SN:2956932040", $aRecord->raw);
        $this->assertEquals("A", $aRecord->type);
        $this->assertEquals("X", $aRecord->manufacturer);
        $this->assertEquals("SX001", $aRecord->unique_id);
        $this->assertEquals(" SKYTRAXX V1.89 SN:2956932040", $aRecord->id_extension);

    }

    public function testHRecord() {

        $inputString = "HOPLTPILOT: Tobias Schmid";
        $hRecord = new \IGCParser\Record\IGC_H_Record($inputString);

        $this->assertEquals("H", $hRecord->type);
        $this->assertEquals($inputString, $hRecord->raw);
        $this->assertEquals("O", $hRecord->source);
        $this->assertEquals("PLT", $hRecord->tlc);
        $this->assertEquals("PILOT", $hRecord->key);
        $this->assertEquals(" Tobias Schmid", $hRecord->value);


        $inputString = "HOGTYGLIDERTYPE: Ozone Jomo";
        $hRecord = new \IGCParser\Record\IGC_H_Record($inputString);

        $this->assertEquals("H", $hRecord->type);
        $this->assertEquals($inputString, $hRecord->raw);
        $this->assertEquals("O", $hRecord->source);
        $this->assertEquals("GTY", $hRecord->tlc);
        $this->assertEquals("GLIDERTYPE", $hRecord->key);
        $this->assertEquals(" Ozone Jomo", $hRecord->value);

        // TODO: extend further types


    }

}















