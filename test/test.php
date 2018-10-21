<?php

declare(strict_types=1);

use IGCReader\IGCReader;
use PHPUnit\Framework\TestCase;


final class IGCReaderTest extends TestCase {


    public function testFileHasBeenFound() {

        $igcFile = new IGCReader(__DIR__ . "/example_files/8ALTobi1.igc");

        var_dump($igcFile);

        try {
            var_dump($igcFile->getTrackPoints());
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        $this->assertNotNull($igcFile);

    }

}















