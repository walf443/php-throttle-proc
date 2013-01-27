<?php

error_reporting(E_ALL);

require_once(__DIR__ . "/../src/ThrottleProc.php");

final class ThrottleProcTest extends \PHPUnit_Framework_TestCase {
    function test_calcSleepTime(){
        $result = ThrottleProc::calcSleepTime(0.1, 1.0);
        $this->assertEquals(9.0, $result);

        $result = ThrottleProc::calcSleepTime(0.2, 1.0);
        $this->assertEquals(4.0, $result);

        $result = ThrottleProc::calcSleepTime(0.5, 1.0);
        $this->assertEquals(1.0, $result);

        $result = ThrottleProc::calcSleepTime(1.0, 1.0);
        $this->assertEquals(0, $result);
    }

    function test_start() {
        $throttle = new ThrottleProc(0.1);
        $throttle->start();
        $this->assertTrue(true, "start should not occur any error");
    }

    /**
     *
     * @expectedException LogicException
     */
    function test_finishAndSleep__in_case_without_calling_start() {
        $throttle = new ThrottleProc(0.1);
        $throttle->finishAndSleep();
    }

    function test_functional() {
        $throttle = new ThrottleProc(0.1);
        $i = 0;
        while ( $i < 10000 ) {
            $throttle->start();
            $throttle->finishAndSleep();
            $i++;
        }
        // echo gettimeofday(true) - $throttle->startTime; // please comment out if you want to show total time.
        $this->assertTrue(true, "this test should be passed");
    }
}
