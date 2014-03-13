<?php  
/**
 	HTML  
    
    HTML::css('//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css');
	HTML::css(base_url().'css/admin.css');
	HTML::js('//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js');
	HTML::code("
		$('b').click(function(){
			alert(1);
		});
	");
		//输出css 链接
		HTML::link('css');
		//输出 js 链接
		HTML::link('js');
		//输出 css 代码
		HTML::code('css');
		//输出 js 代
		HTML::code('js');
	
	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
*/
namespace Ken\Web; 
class HTML
{ 
   static $obj; 
   static $code;
   static $api;
   static $host = ''; 
   static $output = [];
   /**
	   	HTML::api( F::get('API')->post('widget',[
			'name'=>'jwplayer',
			'id'=>'ss',
			'par'=>['test'=>1]
		]) );
		
		HTML::api();
   
   */
   static function api($data = null){
   	    if($data){
	   		$arr = json_decode($data,true);
	   		static::$api[] = $arr; 
	   		return ;
   		}
   		if(static::$api){
   			foreach(static::$api as $v){
   				static::$code = $v['code'];
   				static::$obj = $v['obj']; 
   				static::output(); 
   			}
   		}
   }
   /**
   	输出
   */
   static function output($type = null ,$par = []){
   	   $css = substr($type,-4);
   	   $js = substr($type,-3);
   	   if(substr($type,-4) == '.css'){
   	   	    $par['href'] = $type;
   	   	    $par['rel'] = 'stylesheet';
   	   		static::element('link',$par,true);
   	   		return ;
   	   }elseif(substr($type,-3) == '.js'){
   	   	    $par['src'] = $type;
   	   	    $par['type'] = 'text/javascript';
   	   		static::element('script',$par,true);
   	   		return ;
   	   	
   	   } 
   	   if($type){
			static::link($type); 
			static::code($type);
			return ;   	   
   	   }
	   	static::link('css'); 
		static::code('css');
		static::code('js');
		static::link('js');
   }
   static function _http($str){
   		if(strpos($str,'http://') !== false || strpos($str,'https://') !== false || substr($str,0,2)=='//'){
   			return true;
   		}
   		return false;
   }
   /**
   		写CODE 与输出CODE
   */
   static function code($code){
   	   if(in_array($code,['css','js'])) return static::codes($code);    
   	   $id = md5($code);
   	   $code = trim($code);
   	   $type = 'style';
   	   $par['type'] = "text/css";
   	   //js code 
   	   if(substr($code,0,1) == '$' || substr($code,0,4)=="var "){
   	   		$type = 'script';
   	   		$par['type'] = "text/javascript";
   	   } 
   	   static::$code[$type][$id]['type'] = $type; 
   	   static::$code[$type][$id]['code'] = $code;  
   	   static::$code[$type][$id]['par'] = $par;  
   }
   
   static function codes($type='css'){
   	   $arr['css'] = 'style';
   	   $arr['js'] = 'script'; 
   	   $type = $arr[$type];
	   $code = static::$code[$type]; 
	   if(!$code) return;
   	   foreach($code as $v){  
   			$string  .= "\n".$v['code']."\n";
   		}
   		if($type=="script") {
   			$string = "\n$(function(){\n $string \n});";
   		} 
   		echo static::element($v['type'],$v['par'],true,$string); 
   }
   
   /**
		创建表单元素
	*/
   static function element($type, $par =[] , $close = false ,$value = null){ 
		foreach($par as $k=>$v){ 
			$str .= " $k='{$v}' ";
		}
		//避免重复输出
		if(isset(static::$output[md5($str)])) return false;
		static::$output[md5($str)] = true;
		if(true === $close)
			$close = "</{$type}>";
		echo "<{$type} $str>".$value.$close."\n";
   }
  
   static function css($url,$par = ['rel'=>'stylesheet']){  
   	   if(static::_http($url) === false)
   	   	   $url = static::$host.$url; 
   	   $par['href'] = $url; 
   	   static::$obj['link'][md5($url)] = $par;
   	    
   }
   static function js($url ,$par = ['type'=>'text/javascript']){  
   	   if(static::_http($url) === false)
   	   	   $url = static::$host.$url; 
   	   $par['src'] = $url;
   	   static::$obj['script'][md5($url)] = $par; 
   }
    
   
   static function link($name = 'css'){   
   	   $arr['css'] = 'link';
   	   $arr['js'] = 'script';
   	   $name = $arr[$name];
   	   $all = static::$obj[$name];
   	   $close = false;
   	   if($name == 'script') $close = true;
   	   if($all){
   	   		foreach($all as $par){
   	   			 static::element($name,$par,$close);
   	   		}
   	   }
   }
   
   
}