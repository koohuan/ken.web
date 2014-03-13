<?php 
/** 
	依赖 F.php 与 Crypt.php
	需要先session_start();
	使用方法
	需先注册crypt到F中
	
	F::set('crypt',function() use ($crypt){ 
		return new Crypt('abc');  
	});
	
    Session 
    
 	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
 */
namespace Ken\Web;
class Session
{  
	static function start(){
		session_start();
	}
  	/**
 		是否存在 flash message session
	*/
	static function has_flash($name){ 
		$name = 'flash_message_'.$name;
		if(static::get($name))
			return true;
		return false;
	}
	/**
		设置 flash message session
	*/
	static function flash($name,$value = null){
		$name = 'flash_message_'.$name;
		if($value){		
			static::set($name,$value);
		}else{		 
			$value = static::get($name);  
	 		static::delete($name); 
		}
		return $value;
	}
	
	/**
 		设置SESSION
	*/
	static function set($name,$value){  
		$value = F::get('crypt')->set($value);
		$_SESSION[$name] = $value;
	}
	/**
		取回SESSION
	*/
	static function get($name){ 
		$value = $_SESSION[$name];
		if($value)
			return trim(F::get('crypt')->get($value));		 
	}
	/**
		 删除SESSION，只能删除一个SESSION
	*/
	static function delete($name){
		unset($_SESSION[$name]); 
	}
	
	
 
}