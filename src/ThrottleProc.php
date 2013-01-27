<?php

/**
 *
 * Copyright (C) 2013 by Keiji Yoshimi
 *
 * This code is originaly copyed from perl's Sub::Throttle library.
 *
 * Copyright (C) 2008 by Cybozu Labs, Inc.
 * This library is free software; you can redistribute it and/or modify it under the same terms as Perl itself, either Perl version 5.8.6 or, at your option, any later version of Perl 5 you may have available.
 *
 * @example 
 *    $throttle = new ThrottleProc();
 *    while ( $data = $db->fetch() ) {
 *      $throttle->start();
 *      // write code that you want to throttle.
 *      // ...
 *      $throttle->finishAndSleep();
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

    public function finishAndSleep()
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

