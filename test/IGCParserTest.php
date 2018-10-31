<?php

declare(strict_types=1);

use IGCParser\IGCParser;
use PHPUnit\Framework\TestCase;


final class IGCParserTest extends TestCase {


    public function testLoadFromFile() {

        $igcFile = IGCParser::fromFile(__DIR__ . "/example_files/8ALTobi1.igc");

        $this->assertNotNull($igcFile);
        $this->assertEquals("Tobias Schmid", $igcFile->pilot);
        $this->assertEquals("Ozone Jomo", $igcFile->glider_type);

        var_dump($igcFile);

        try {
            var_dump($igcFile->getTrackPoints());
        } catch (Exception $e) {
            echo $e->getMessage();
        }

    }

    public function testLoadFromString() {


        $igcFileString = file_get_contents(__DIR__ . "/example_files/8ALTobi1.igc");

        $this->assertTrue(is_string($igcFileString));


        $igcFile = IGCParser::fromString($igcFileString);

        $this->assertNotNull($igcFile);
        $this->assertEquals("Tobias Schmid", $igcFile->pilot);
        $this->assertEquals("Ozone Jomo", $igcFile->glider_type);


        try {
            var_dump($igcFile->getTrackPoints());
        } catch (Exception $e) {
            echo $e->getMessage();
        }



    }

}















