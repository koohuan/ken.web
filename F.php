<?php
/** 
  	closure 静态化，可延时加载。
  	如数据库操作类，只有在F::get('db') 时才会启用连接
 	核心加载类
	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014
*/
namespace Ken\Web;

class F{  
 
	static $app;
 	
	public static function get($key){
		$new = $key.'.closure';
		$r = static::$app[$new];
		if(!isset(static::$app[$new])){ 
			$v = static::$app[$key]; 
			if($v)
				static::$app[$new] = $v();
		}
		return static::$app[$new];
	}
	public static function set($key , $value){ 
		return static::$app[$key] = $value;
	}
	 
	
}