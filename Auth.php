<?php 
/**  
 	
	DROP TABLE IF EXISTS `admin`;
	CREATE TABLE `admin` (
	  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  `email` varchar(50) NOT NULL,
	  `password` varchar(64) NOT NULL,
	  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	  `update_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	INSERT INTO `admin` (`id`, `email`, `password`, `create_at`, `update_at`) VALUES
	(1,	'admin',	'af23ceba354f25da4e1f5a742dd13651a0797fac',	'2014-06-18 16:27:18',	'0000-00-00 00:00:00');
 	
	
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