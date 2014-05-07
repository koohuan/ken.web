<?php
/**
    读取配置
	Config::path(base_path().'/../api/');
 
    Config::get('app.error_reporting'); 

	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014
*/  
namespace Ken\Web; 
class Config{ 
	
	static $_config;
	static $path;
	static function path($path){
		if(!isset(static::$path))
			static::$path = $path; 
	}
	static function get($alias){
		return static::load($alias);
	}
	static function set($alias,$value){
		 $id = md5($alias);
		 static::$_config[$id] = $value; 
	}
	/**
	* 直接加载文件，并缓存.
	* 目录相对composer.json
	* <code>
	* Config::load('application.timezone'); return value
	* Config::load('application');          return array
	* </code>
	*/ 
	static function load($alias){  
		$id = md5($alias);
		if(static::$_config[$id]) return static::$_config[$id];
		if(strpos($alias , '.') !== false){
			$key = substr($alias,strpos($alias , '.')+1); 
			$alias = substr($alias,0,strpos($alias , '.'));   
		}
		if(!isset(static::$_config[$id])){
			$path = static::$path?:base_path(); 
			$file = $path.'/config/'.str_replace('.','/',$alias).'.php';  
			if(file_exists($file)){ 
				static::$_config[$id] = include $file;
			}  
		}  
		$value = static::$_config[$id]; 
		if($key){ 
			if(strpos($key , '.') !== false){  
				$arr = explode('.',$key);  
				foreach($arr as $v){
					$value = $value[$v];
				}   
				static::$_config[$id] = $value;
				return $value; 
			}
			static::$_config[$id] = $value[$key];
			return $value[$key];
		}
		static::$_config[$id] = $value;
		return $value;
	}
  

}