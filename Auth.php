<?php 
/**  
	Auth
	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
 */
namespace Ken\Web; 
 
class Auth
{ 
	static $obj; 
    public static function __callStatic($name, $arguments) 
    {   
    	if ( ! static::$obj)
    		 static::$obj = new \Ken\Web\Auth_Class;   
    	return call_user_func_array( array(static::$obj , $name) , $arguments);  
    }  
}