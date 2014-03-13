<?php  
/**
	依赖  Arr
	
    Event,如果Event::set('init.application',function(){}) 带 说明是数组 
   	Event::get('init.');
   	composer.json 如下 对应 event 目录
	
	"autoload": {  
	    "psr-4":{ 
	        "app\\": "app/",
		    "lib\\": "lib/",
		    "widget\\": "widget/",
		    "event\\": "event/" 	
	    }
	}
	
	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
*/
namespace Ken\Web; 
class Event
{ 
   static $event;
   /**
   	自动加载Event namesapce
   	$name user@login 
   	文件所有路径为 /event/user.php 
   */
   static function load($name,$par = [] ,$namespace = "\\event\\"){
   	   $name = str_replace('/','\\',$name);
   	   $arr = explode('@',$name); 
   	   $cls = $namespace.$arr[0];
   	   $obj = new $cls;
   	   call_user_func_array([$obj,$arr[1]],$par); 
   }
   /**
   *  
   * 以.分隔，且只能出现一次，调用时直接使用 Event::get('init');
   * Event::set('init.node_content',function(){ 
   * })
   */
   static function set($name,$fun){  
   	 if(strpos($name,'.')===false){
   	 	static::$event[$name] = $fun;
   	 	return;
   	 }
   	 $a = substr($name,0,strrpos($name,'.')); 
   	 $b = substr($name,strrpos($name,'.')+1);  
	 static::$event[$a][$b] = $fun;
   }
   /**
      执行event
   	  Event::get('init.');或  Event::get('init*');
   */
   static function get($name='init',$object = null){   
   	   $name = str_replace('*','.',$name);
   	   if(strpos($name,'.')===false){ 
   	   		$v = static::$event[$name]; 
   	   		return $v($object); 
   	   }
   	   $name = substr($name,0,strrpos($name,'.')); 
   	   if(!static::$event[$name]) return;  
   	   foreach(static::$event[$name] as $k=>$v){ 
   	   		 $out[] = $v($object);
   	   }  
   	   return static::merge($out);
 	}
 	
 	static function merge($a){
 		foreach($a as $k=>$v){
 			foreach($v as $_k=>$_v){
 				$new[$_k] = $_v;
 			}
 		}
 		return $new;
 	}
}