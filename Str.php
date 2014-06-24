<?php 
/**
	字符串
 
	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
 */
namespace Ken\Web;
class Str
{ 
	/**
	 * Generate an ID, constructed using:
	 *   a 4-byte value representing the seconds since the Unix epoch,
	 *   a 3-byte machine identifier,
	 *   a 2-byte process id, and
	 *   a 3-byte counter, starting with a random value.
	 * Just like a MongoId string.
	 *
	 * @link http://docs.mongodb.org/manual/reference/object-id/
	 * @return string 24 hexidecimal characters
	 */
	static function id()
	{
	    static $i = 0;
	    $i OR $i = mt_rand(1, 0x7FFFFF);
	 
	    return sprintf("%08x%06x%04x%06x",
	        /* 4-byte value representing the seconds since the Unix epoch. */
	        time() & 0xFFFFFFFF,
	 
	        /* 3-byte machine identifier.
	         *
	         * On windows, the max length is 256. Linux doesn't have a limit, but it
	         * will fill in the first 256 chars of hostname even if the actual
	         * hostname is longer.
	         *
	         * From the GNU manual:
	         * gethostname stores the beginning of the host name in name even if the
	         * host name won't entirely fit. For some purposes, a truncated host name
	         * is good enough. If it is, you can ignore the error code.
	         *
	         * crc32 will be better than Times33. */
	        crc32(substr((string)gethostname(), 0, 256)) >> 16 & 0xFFFFFF,
	 
	        /* 2-byte process id. */
	        getmypid() & 0xFFFF,
	 
	        /* 3-byte counter, starting with a random value. */
	        $i = $i > 0xFFFFFE ? 1 : $i + 1
	    );
	}  
	/**
	* 生成用户唯一ID
	*/
	static function uid()
	{
	 	return static::id();
	}
	/**
	* 生成不重复订单ID
	*/
	static function order_id()
	{
		mt_srand((double) microtime() * 1000000);  
        return date('Ymdhis') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT).\User::get()['id'];   
	}
	
	/**
	* currencies 
	* 
	* @link http://xurrency.com/currencies currencies
	*
	* Example:
	* <code> 
	* Str::money(1,'eur','rmb');
	* </code>  
	* @param  int $num   100
	* @param  string $from  params 
	* @param  string $to  params 
	*/
   static function money($num,$from, $to ){
		$url = "http://www.google.com/ig/calculator?hl=en&q=".$num.$from."=?".$to;
		$data = file_get_contents($url);
		$data = explode('"', $data);
		$data = explode(' ', $data['3']);
		$var = $data['0'];
		return round($var,2); 
   }
    /**
	  截取字符串
	*/
	static function cut($str, $length,$ext='...') {
		$str = trim(strip_tags($str));
		global $s;
		$i = 0;
		$len = 0;
		$slen = strlen($str);
		$s = $str;
		$f = true; 
		while ($i <= $slen) {
			if (ord($str{$i}) < 0x80) {
				$len++; $i++;
			} 
			else if (ord($str{$i}) < 0xe0) {
				$len++; $i += 2;
			} 
			else if (ord($str{$i}) < 0xf0) {
				$len += 2; $i += 3;
			} 
			else if (ord($str{$i}) < 0xf8) {
				$len += 1; $i += 4;
			} 
			else if (ord($str{$i}) < 0xfc) {
				$len += 1; $i += 5;
			} 
			else if (ord($str{$i}) < 0xfe) {
				$len += 1; $i += 6;
			}

			if (($len >= $length - 1) && $f) {
				$s = substr($str, 0, $i);
				$f = false;
			}

			if (($len > $length) && ($i < $slen)) {
				$s = $s . $ext; break;  
			}
		}
		return $s;
	}
	function rand_number($j = 4 ){
		$str = null;
		for($i=0;$i<$j;$i++){
			$str .= mt_rand(0,9);
		}
		return $str;
	}
    /**
    	随机字符
    */
	function rand($j = 8){
		$string = "";
	    for($i=0;$i < $j;$i++){
	        srand((double)microtime()*1234567);
	        $x = mt_rand(0,2);
	        switch($x){
	            case 0:$string.= chr(mt_rand(97,122));break;
	            case 1:$string.= chr(mt_rand(65,90));break;
	            case 2:$string.= chr(mt_rand(48,57));break;
	        }
	    }
		return strtoupper($string); //to uppercase
	}
	/**
		组织URL query string		
	*/
	function query_build($arr = []){
		if(!is_array($arr) || !implode('',$arr)) return;
		foreach($arr as $k=>$v){
			$str .="$k=$v&";
		}
		return substr($str,0,-1);
	}
	
 
}