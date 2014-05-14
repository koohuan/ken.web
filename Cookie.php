<?php 
/** 
 
	使用方法
	Cookie::set
	
	//设置COOKIE作用域
	Config::get('app.cookie_domain')
	
	
	
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
	static function set($name,$value,$expire=0,$path='/',$domain=null,$secure=null){ 
		if(Config::get('app.cookie_domain')) $domain = Config::get('app.cookie_domain');
		//设置跨域COOKIE
		header('P3P: CP="NOI DEV PSA PSD IVA PVD OTP OUR OTR IND OTC"');
		if(false !== $value){
			$value = Crypt::encode($value);
		}
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
			return trim(Crypt::decode($value));		 
	}
	/**
	 	删除COOKIE，只能删除一个COOKIE
	*/
	static function delete($name){
		unset($_COOKIE[$name]);
		static::set($name,false,time()-20); 
	}
	
	
 
}