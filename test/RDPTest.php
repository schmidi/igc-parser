<?php

use PHPUnit\Framework\TestCase;

final class RDPTest extends TestCase
{

    public function testSimplifyLine1()
    {


        $line = array(
            new \IGCParser\TrackPoint(150, 10, 0, 0),
            new \IGCParser\TrackPoint(200, 100, 0, 0),
            new \IGCParser\TrackPoint(360, 170, 0, 0),
            new \IGCParser\TrackPoint(500, 280, 0, 0)
        );

        $expectedResult = array(
            new \IGCParser\TrackPoint(150, 10, 0, 0),
            new \IGCParser\TrackPoint(200, 100, 0, 0),
            new \IGCParser\TrackPoint(500, 280, 0, 0)
        );

        $result = \IGCParser\RDPLineSimplification::RDPLineSimplification($line, 30, 0);

        $this->assertEquals($expectedResult, $result);

    }


    public function testBasic2()
    {
        $line = array(
            new \IGCParser\TrackPoint(-30, -40,0, 0),
            new \IGCParser\TrackPoint(-20, -10,0, 0),
            new \IGCParser\TrackPoint(10, 10,0, 0),
            new \IGCParser\TrackPoint(50, 0,0, 0),
            new \IGCParser\TrackPoint(40, -30,0, 0),
            new \IGCParser\TrackPoint(10, -40,0, 0));

        $rdpResult = \IGCParser\RDPLineSimplification::RDPLineSimplification($line, 12);

        $expectedResult = array(
            new \IGCParser\TrackPoint(-30, -40,0, 0),
            new \IGCParser\TrackPoint(10, 10,0, 0),
            new \IGCParser\TrackPoint(50, 0,0, 0),
            new \IGCParser\TrackPoint(40, -30,0, 0),
            new \IGCParser\TrackPoint(10, -40,0, 0));

        $this->assertEquals($expectedResult, $rdpResult, "result polyline array incorrect");

        $rdpResult = \IGCParser\RDPLineSimplification::RDPLineSimplification($line, 15);

        $expectedResult = array(
            new \IGCParser\TrackPoint(-30, -40,0, 0),
            new \IGCParser\TrackPoint(10, 10,0, 0),
            new \IGCParser\TrackPoint(50, 0,0, 0),
            new \IGCParser\TrackPoint(10, -40,0, 0));
        $this->assertEquals($expectedResult, $rdpResult, "result polyline array incorrect");

        $rdpResult = \IGCParser\RDPLineSimplification::RDPLineSimplification($line, 20);

        $expectedResult = array(
            new \IGCParser\TrackPoint(-30, -40,0, 0),
            new \IGCParser\TrackPoint(10, 10,0, 0),
            new \IGCParser\TrackPoint(50, 0,0, 0),
            new \IGCParser\TrackPoint(10, -40,0, 0));
        $this->assertEquals($expectedResult, $rdpResult, "result polyline array incorrect");

        $rdpResult = \IGCParser\RDPLineSimplification::RDPLineSimplification($line, 45);

        $expectedResult = array(
            new \IGCParser\TrackPoint(-30, -40,0, 0),
            new \IGCParser\TrackPoint(10, 10,0, 0),
            new \IGCParser\TrackPoint(10, -40,0, 0));
        $this->assertEquals($expectedResult, $rdpResult, "result polyline array incorrect");
    }

    public function testBasic3()
    {
        $line = array(
            new \IGCParser\TrackPoint(0.0034, 0.013,0, 0),
            new \IGCParser\TrackPoint(0.0048, 0.006,0, 0),
            new \IGCParser\TrackPoint(0.0062, 0.01,0, 0),
            new \IGCParser\TrackPoint(0.0087, 0.009,0, 0));
        $rdpResult = \IGCParser\RDPLineSimplification::RDPLineSimplification($line, 0.001);
        $expectedResult = array(
            new \IGCParser\TrackPoint(0.0034, 0.013,0, 0),
            new \IGCParser\TrackPoint(0.0048, 0.006,0, 0),
            new \IGCParser\TrackPoint(0.0062, 0.01,0, 0),
            new \IGCParser\TrackPoint(0.0087, 0.009,0, 0));
        $this->assertEquals($expectedResult, $rdpResult, "result polyline array incorrect");

        $rdpResult = \IGCParser\RDPLineSimplification::RDPLineSimplification($line, 0.003);
        $expectedResult = array(
            new \IGCParser\TrackPoint(0.0034, 0.013,0, 0),
            new \IGCParser\TrackPoint(0.0048, 0.006,0, 0),
            new \IGCParser\TrackPoint(0.0087, 0.009,0, 0));
        $this->assertEquals($expectedResult, $rdpResult, "result polyline array incorrect");

        $rdpResult = \IGCParser\RDPLineSimplification::RDPLineSimplification($line, 0.01);

        $expectedResult = array(
            new \IGCParser\TrackPoint(0.0034, 0.013,0, 0),
            new \IGCParser\TrackPoint(0.0087, 0.009,0, 0));
        $this->assertEquals($expectedResult, $rdpResult, "result polyline array incorrect");
    }


}