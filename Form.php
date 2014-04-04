<?php 
/**  
    Form 
    
    echo \Form::select('a',['option'=>$widget]);
    
 	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
 */
namespace Ken\Web;
class Form
{  
	static  $par = [];
	/**
		加载数据
	*/
 	static function load($arr=[]){
 		if($arr){
 			foreach($arr as $k=>$v){
 				$v = trim($v);
 			}
 		}
 	}
	/**
		创建表单元素
	*/
	static function element($name = null , $type, $close = false,$value=null){
		if($name){
			static::$par['id'] = $name;
			static::$par['name'] = $name; 
		}
		if(static::$par){
			foreach(static::$par as $k=>$v){
				if($v)
					$str .= " $k='{$v}' ";
			}
		}
		if(true === $close)
			$close = "</{$type}>";
		return "<{$type} $str>".$value.$close;
	}
 	
 	static function __callStatic($method,$par = []){  
  		$name = $par[0]; 
  		static::$par = $par[1];  
 		//自动加载POST的值 
 		if(!static::$par['value']){   
	 				static::$par['value'] =  $_POST[$name];
	 		 	if(!static::$par['value'])
	 				static::$par['value'] =  $_GET[$name]; 
		} 
		$value = static::$par['value'];
 		switch($method){
 			case 'open':
 				unset(static::$par['value']);
 				static::$par['method'] = static::$par['method']?:'POST';
 				return static::element($name,'form');
 				break;
 			case 'close':
 				return "</form>";
 				break;
 			case 'label': 
 				return static::element(NULL,'label',true,$name);
 				break;
 			case 'submit':
 				static::$par['value'] = $name?:'submit'; 
 				static::$par['type'] = 'submit'; 
 				return static::element(null,'input');
 				break;
 			case 'input': 
 				return static::element($name,'input');
 				break;
 			case 'password':
 				static::$par['type'] = 'password';
 				return static::element($name,'input');
 				break;
 			case 'text': 
 				unset(static::$par['value']);
 				return static::element($name,'textarea',true,$value);
 				break;
 			case 'select':
 				$option = static::$par['option'];
 				unset(static::$par['option']);
 				foreach($option as $v=>$label){ 
 					$selected = null;
 					if($v==$value)  $selected = "selected";
 					$str .= "<option value='".$v."' $selected >".$label."</option>";
 				}
 				return static::element($name,'select',true,$str);
 				break;
 		}
 		 
 	}
  
 

	
 
}