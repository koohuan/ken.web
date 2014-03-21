<?php 
/** 
	依赖 F.php 与 Crypt.php
	使用方法
	需先注册crypt到F中
	
	F::set('crypt',function() use ($crypt){ 
		return new Crypt('abc');  
	});
	
    Cookie 
    
 	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
 */
namespace Ken\Web;
class Cookie
{  
  	/**
		设置COOKIE 
		浏览器关闭就会自动失效
	*/
	static function set($name,$value,$expire=0,$path='/',$secure=null){ 
		$value = F::get('crypt')->set($value);
		setcookie($name,$value,$expire,$path,$domain,$secure);
		if($value)
			$_COOKIE[$name] = $value;
	}
	/**
		设置永久 COOKIE  
	*/
	static function forver($name,$value,$path='/',$secure=null){ 
		static::set($name,$value,time()+86400*365*100,$path,$secure=null);
	}
	
	/**
	 	取回COOKIE
	*/
	static function get($name){
		$value = $_COOKIE[$name]; 
		if($value)
			return trim(F::get('crypt')->get($value));		 
	}
	/**
	 	删除COOKIE，只能删除一个COOKIE
	*/
	static function delete($name){
		unset($_COOKIE[$name]);
		static::set($name,false,time()-20); 
	}
	
	
 
}