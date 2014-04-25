<?php 
/**
    会员权限
 	 
	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
 */
namespace Ken\Web;
 
class User
{ 
	
	static $obj; 
    public static function __callStatic($name, $arguments) 
    {   
    	if ( ! static::$obj)
    		 static::$obj = new \Ken\Web\Auth_Class;   
    	static::$obj->table = 'users';
    	static::$obj->cookie = true;
    	static::$obj->username = null;
    	return call_user_func_array( array(static::$obj , $name) , $arguments);  
    }  
   
  
    
 
}