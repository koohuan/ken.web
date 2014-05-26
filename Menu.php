<?php
/**
  	菜单

	Menu::set($name);
	
	Menu::active($name);
	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
*/
namespace Ken\Web;

class Menu{ 
 	static $menu;
 	//判断是否选中
 	static function active($name){
 		if(!static::$menu) return false;
 		foreach(static::$menu as $n){
 			if(strpos($n,$name)!==false) return true;
 		}
 	}
 	
 	static function set($name){
 		if(is_array($name)){
 			foreach($name as $n){
 				static::$menu[$n] = $n;
 			}
 		}else{
 			static::$menu[$name] = $name;
 		}
 	}
	
}