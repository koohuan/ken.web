<?php 
/** 
 	Response::code(500);
 	Response::code(200);
    //ȡ�������״̬ 
    \Response::get_code() == 200
    
 	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
 */
namespace Ken\Web;
class Response
{  
	static $obj;
	public static function __callStatic($name, $arguments) 
    {   
    	if ( ! static::$obj)
    		 static::$obj = new \Ken\Web\ResponseCode;    
    	return call_user_func_array( array(static::$obj , $name) , $arguments);  
    }  

	
 
}