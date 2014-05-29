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
 	static $to_str;
 	/**
 		多维数组转成字符串
 		\Arr::to_str([]);
 	*/
 	static function to_str($arr ,$suffix = "\n", $rest = false){ 
 		if(false === $rest) static::$to_str = null;
 		if(!is_array($arr)) return $arr;
 		foreach($arr as $v){  
 			if(!is_array($v)) static::$to_str .= $v . $suffix;
 			if(is_array($v) && static::deep($v)==1){ 
 				static::$to_str .= implode($suffix,$v);
 			}else{
 				static::to_str($v , $suffix , true);
 			} 			
 		} 
 		return  static::$to_str; 
 	}
 	
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
  	static function deep($arr = []){  
 	 	$max = 1; 
        foreach ($arr as $v) {
            if (is_array($v)) {
                $deep = static::deep($v) + 1; 
                if ($deep > $max)  
                    $max = $deep; 
            }
        }        
        return $max; 
	}
	
 
}