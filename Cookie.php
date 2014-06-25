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
	static function set($name,$value=null,$expire=0,$path='/',$domain=null,$secure=null){  
		if(Config::get('app.cookie_domain')) $domain = Config::get('app.cookie_domain');
		//设置跨域COOKIE
		header('P3P: CP="NOI DEV PSA PSD IVA PVD OTP OUR OTR IND OTC"');
		/**
		* 对数组或对象直接设置COOKIE
		*/
		if(!$value && (is_array($name) || is_object($name))){
			$name = (array)$name; 
			foreach($name as $k=>$v){
				$v = Crypt::encode($v);
				$_COOKIE[$k] = $v;
				setcookie($k,$v,$expire,$path,$domain,$secure);
			}  
			return $name;
		}
		
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
	static function get($name = null){
		if(!$name && $_COOKIE) { 
			foreach($_COOKIE as $k=>$v){
				if(Crypt::decode($v))
					$data[$k] = Crypt::decode($v);
				else
					$data[$k] = $v;
			} 
			return  $data;
		}  
		$value = $_COOKIE[$name]; 
		if($value)
			return Crypt::decode($value);		 
	}
	/**
	 	删除COOKIE，只能删除一个COOKIE
	*/
	static function delete($name = null){
		if(!$name)
			$values = $_COOKIE;
		elseif(is_array($name))
			$values = array_flip($name);		
		if($values){
			foreach($values as $name=>$value){
				unset($_COOKIE[$name]);
				static::set($name,false,time()-20); 
			} 
			return;
		} 
		unset($_COOKIE[$name]);
		static::set($name,false,time()-20); 
	}
	
	
 
}