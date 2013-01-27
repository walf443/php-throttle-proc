<?php

/**
 *
 * @example 
 *    $throttle = new ThrottleProc();
 *    while ( $data = $db->fetch() ) {
 *      $throttle->start();
 *      // write code that you want to throttle.
 *      // ...
 *      $throttle->stopAndSleep();
 *    }
 *
 */

class ThrottleProc {
    const SECOND_TO_MICRO_SECONDS = 1000000;

    /**
    * @param $load : float 
    *   load is 0 ~ 1. If you set load to 0.1, your task take 10 times. If you set load to 0.9, your task take 1.1 times.
    */
    function __construct($load)
    {  
        $this->load = $load;
        $this->startTime = null;
    }

    public function start()
    {  
        $this->startTime = gettimeofday(true);
    }

    public function stopAndSleep()
    {  
        if ( !isset($this->startTime) ) {
            throw new \LogicException("please call start() before call this method.");
        }
        $finish_time = gettimeofday(true);
        $stime = self::calcSleepTime($this->load, $finish_time - $this->startTime);
        if ( $stime > 0 ) {
            usleep($stime * self::SECOND_TO_MICRO_SECONDS);
        }
    }

    public static function calcSleepTime($load, $time)
    {  
        if ( $time > 0.0 ) {
            return $time * ( 1 - $load ) / $load;
        } else {
            return 0;
        }
    }

}

