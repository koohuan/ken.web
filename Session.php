<?php 
/** 
	 
	需要先session_start();
	使用方法
	 
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
	static function set($name,$value = null){  
		/**
		* 对数组或对象直接设置COOKIE
		*/
		if(!$value && (is_array($name) || is_object($name))){
			foreach($name as $k=>$v){
				$v = Crypt::encode($v);
				$_SESSION[$k] = $v; 
			} 
			return $name;
		}
		$value = Crypt::encode($value);
		$_SESSION[$name] = $value;
	}
	/**
		取回SESSION
	*/
	static function get($name = null){
		if(!$name) {
			if($_SESSION){
				foreach($_SESSION as $k=>$v){
					if(Crypt::decode($v))
						$data[$k] = Crypt::decode($v);
					else
						$data[$k] = $v;
				}
			}
			return  $data;
		} 
		$value = $_SESSION[$name];
		if($value)
			return Crypt::decode($value);		 
	}
	/**
		 删除SESSION，只能删除一个SESSION
	*/
	static function delete($name = null){
		if(!$name)
			$values = $_SESSION;
		elseif(is_array($name))
			$values = array_flip($name);
		if($values){
			foreach($values as $name=>$value){
				unset($_SESSION[$name]); 
			}
			return;
		}  
		unset($_SESSION[$name]); 
	}
	
	
 
}