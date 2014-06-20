<?php
/** 
  
	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014
*/
namespace Ken\Web; 
class Url{  
	static function short($b){
	    $i = static::tke($b);
	    $i = $i >> 2 & 1073741823;
	    $i = $i >> 4 & 67108800 | $i & 63;
	    $i = $i >> 4 & 4193280 | $i & 1023;
	    $i = $i >> 4 & 245760 | $i & 16383;
	    $j = "7";
	    $h = static::tkf($b);
	    $k = ($i >> 2 & 15) << 4 | $h & 15;
	    $k |= ($i >> 6 & 15) << 12 | ($h >> 8 & 15) << 8;
	    $k |= ($i >> 10 & 15) << 20 | ($h >> 16 & 15) << 16;
	    $k |= ($i >> 14 & 15) << 28 | ($h >> 24 & 15) << 24;
	    $j .= static::tkd($k);
	    return $j;
	}
	
	static function tkc(){
	    $l = 0;
	    foreach (func_get_args() as $val) {
	        $val &= 4294967295;
	        $val += $val > 2147483647 ? -4294967296 : ($val < -2147483647 ? 4294967296 : 0);
	        $l   += $val;
	        $l   += $l > 2147483647 ? -4294967296 : ($l < -2147483647 ? 4294967296 : 0);
	    }
	    return $l;
	}
	static function tkd($l){
	    $l = $l > 0 ? $l : $l + 4294967296;
	    $m = "$l";  //must be a string
	    $o = 0;
	    $n = false;
	    for($p = strlen($m) - 1; $p >= 0; --$p){
	        $q = $m[$p];
	        if($n){
	            $q *= 2;
	            $o += floor($q / 10) + $q % 10;
	        } else {
	            $o += $q;
	        }
	        $n = !$n;
	    }
	    $m = $o % 10;
	    $o = 0;
	    if($m != 0){
	        $o = 10 - $m;
	        if(strlen($l) % 2 == 1){
	            if ($o % 2 == 1){
	                $o += 9;
	            }
	            $o /= 2;
	        }
	    }
	    return "$o$l";
	}
	static function tke($l){
	    $m = 5381;
	    for($o = 0; $o < strlen($l); $o++){
	        $m = static::tkc($m << 5, $m, ord($l[$o]));
	    }
	    return $m;
	}
	static function tkf($l){
	    $m = 0;
	    for($o = 0; $o < strlen($l); $o++){
	        $m = static::tkc(ord($l[$o]), $m << 6, $m << 16, -$m);
	    }
	    return $m;
	}

	
 
	
	
}