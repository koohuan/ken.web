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
 	//生成不重复的ID
	function uuid($suffix_len=3)
	{
		$being_timestamp = 1206576000;
	    $time = explode(' ', microtime());
	    $id = ($time[1] - $being_timestamp) . sprintf('%06u', substr($time[0], 2, 6));
	    if ($suffix_len > 0)
	    {
	        $id .= substr(sprintf('%010u', mt_rand()), 0, $suffix_len);
	    }
	    return $id;
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
		foreach($arr as $k=>$v){
			$str .="$k=$v&";
		}
		return substr($str,0,-1);
	}
	
 
}