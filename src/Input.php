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
   
}