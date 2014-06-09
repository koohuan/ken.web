<?php 
/**
    会员权限
 	User::md5(); 快速加密 
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
    	static::$obj->named = 'Auth';
    	return call_user_func_array( array(static::$obj , $name) , $arguments);  
    }  
   
  
    
 
}