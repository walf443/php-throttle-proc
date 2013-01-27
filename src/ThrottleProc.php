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

    /**
    * @param $load : float 
    *   load is 0 ~ 1. If you set load to 0.1, your task take 10 times. If you set load to 0.9, your task take 1.1 times.
    */
    function __construct($load)
    {  
        $this->load = $load;
        $this->start_time = null;
    }

    public function start()
    {  
        $this->start_time = gettimeofday(true);
    }

    public function stopAndSleep()
    {  
        if ( !isset($this->start_time) ) {
            throw "please call start() before call this method.";
        }
        $finish_time = gettimeofday(true);
        $stime = $this->calcSleepTime($this->load, $finish_time - $this->start_time);
        if ( $stime > 0 ) {
            usleep($stime * 1000000);
        }
    }

    public function calcSleepTime($load, $time)
    {  
        if ( $time > 0.0 ) {
            return $time * ( 1 - $load ) / $load;
        } else {
            return 0;
        }
    }

}

