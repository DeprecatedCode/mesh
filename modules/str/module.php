<?php

/**
 * Mesh :: Str Module
 */
namespace Mesh\str;
use Mesh;

class Module {
    public static function baseconvert($str, $frombase=10, $tobase=36) { 
        $str = trim($str); 
        if (intval($frombase) != 10) { 
            $len = strlen($str); 
            $q = 0; 
            for ($i=0; $i<$len; $i++) { 
                $r = base_convert($str[$i], $frombase, 10); 
                $q = bcadd(bcmul($q, $frombase), $r); 
            } 
        } 
        else $q = $str; 
      
        if (intval($tobase) != 10) { 
            $s = ''; 
            while (bccomp($q, '0', 0) > 0) { 
                $r = intval(bcmod($q, $tobase)); 
                $s = base_convert($r, 10, $tobase) . $s; 
                $q = bcdiv($q, $tobase, 0); 
            } 
        } 
        else $s = $q; 
      
        return $s; 
    }
}
