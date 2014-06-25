<?php 
/** 
	
 	$curl = \Curl::init();
 	$curl->header = true;
	$g = $curl->get($url);
 
	$info = \Helper::http_parse_headers($data);
 	foreach ($info['Set-Cookie'] as $key => $value) {
	}
	
	
	//////////////////////////////////////////////
	
	Curl::get($url);
	Curl::post($url,$data);

	set 设置参数紧跟Curl::
	
	
 	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
 */
namespace Ken\Web;
class Curl
{  
	static $obj;
	function init(){ 
		return new CurlCode;
	}
	public static function __callStatic($name, $arguments) 
    {    
    	static::$obj = new CurlCode;    
    	return call_user_func_array( array(static::$obj , $name) , $arguments);  
    }  

	
 
}