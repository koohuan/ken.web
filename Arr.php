<?php 
/**
     数组
 
	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
 */
namespace Ken\Web;
class Arr
{ 
 	static $deep;
 	
 	static function get($arr = [] , $leep = 1){
  		if(!$arr) return ;
  		$i = 0;
  		foreach($arr as $v){
  			$i++;
  			$vo[] = $v;
  			if($leep == $i){
  				return $vo;
  			} 
  		}
  	}
  	
  	static function first($arr = []){
  		if(!$arr) return ;
  		foreach($arr as $v){
  			return $v;
  		}
  	}
  	/**
  		数组深度
  	*/
  	static function deep($arr = array()){
		foreach($arr as $v){
			static::$deep++;
			if(is_array($v))
				static::deep($v);
			goto a;
		}
		a:
		return static::$deep;
	}
	
 
}