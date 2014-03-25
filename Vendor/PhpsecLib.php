<?php 
/**
    http://phpseclib.sourceforge.net/crypt/examples.html
     
    
    
 	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
*/
namespace Ken\Web\Vendor;
class PhpsecLib{   
	static $_obj;
	static $type;
	static $key;
	static function type($type){
		self::$type = $type;
	}
	static function key($key){
		self::$key = $key;
	}
	static function init() {  
		if (self::$_obj === null) { 
			if(!self::$type) self::$type = "AES";
			if(!self::$key) self::$key = "AES";  
			import(__DIR__.'/PhpsecLib/Crypt/'.self::$type.'.php');  
			$cls = "Crypt_".self::$type;
			$ecb = "CRYPT_".self::$type."_MODE_ECB";
			self::$_obj = new $cls ($ecb);
			self::$_obj->setKey(self::$key);
             
        } 
		return self::$_obj;
	} 
	static function encode($value){
		return base64_encode(self::init()->encrypt($value));
	}
	static function decode($value){
		return self::init()->decrypt(base64_decode($value));
	}
 
 
}