<?php  
/** 
	Input
	    
	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
*/
namespace Ken\Web; 
 
class Input
{ 
	 
   public static function get($key){
   		return trim($_GET[trim($key)])?:null;
   }
   
   public static function post($key){
   		return trim($_POST[trim($key)])?:null;
   }
   
   public static function put($key = null){
   	   	parse_str(file_get_contents("php://input"),$post_vars);
   	   	if($key)
   	   		return $post_vars[$key];
   		return $post_vars;
   }
   
   
}