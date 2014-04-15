<?php 
/**
    会员权限
 	依赖 F.php  DB.php Cookie.php Session.php
 	
 	$db = [
		["mysql:dbname=wei;host=127.0.0.1","test","test"],
		["mysql:dbname=wei2;host=127.0.0.1","test","test"],
	];
 	F::set('db',function() use ($db){
		$config = $db[0];
		return new DB($config[0],$config[1],$config[2]);  
	});
 	
 	
 	CREATE TABLE IF NOT EXISTS `users` (
	  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  `username` varchar(50) NOT NULL,
	  `password` varchar(64) NOT NULL,
	  `email` varchar(50) NOT NULL,
	  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	  `update_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
	

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
    	return call_user_func_array( array(static::$obj , $name) , $arguments);  
    }  
   
  
    
 
}