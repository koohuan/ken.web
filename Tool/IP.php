<?php 
/**
    ip
 
	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
 */
namespace Ken\Web\Tool;
class IP
{ 
  
  	static function get($ip){
		return json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
  	}
   
	
 
}