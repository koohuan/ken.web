<?php 
/**
    http://phpseclib.sourceforge.net/crypt/examples.html
     
    
    
 	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
*/
namespace Ken\Web\Vendor;
use Ken\Web\Config;
class PhpsecLib{   
	static $_obj; 

	static function init() {  
		if (self::$_obj === null) { 
			$type = Config::get('app.crypt_type')?:"AES";
			import(__DIR__.'/PhpsecLib/Crypt/'.$type.'.php');  
			$cls = "Crypt_".$type;
			$ecb = "CRYPT_".$type."_MODE_ECB";
			self::$_obj = new $cls ($ecb);
			self::$_obj->setKey(Config::get('app.crypt_key')?:'abc');
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