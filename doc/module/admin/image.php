<?php
/**
 	直接生成图片
 	无需权限
 	
 	RewriteCond %{REQUEST_FILENAME} !\.(jpg|jpeg|png|gif|bmp)$ 
	RewriteRule /upload/image/(.*)$ /admin/image?id=upload/image/$1 [NC,R,L]  



	@auth Kang Sun <68103403@qq.com>
 	@date 2014
 	
	
*/
namespace Ken\Web\doc\module\admin;  
class image extends \Controller{ 
   
  	
 	function indexAction(){   
 	 	 \Image::cache($_GET['id']);
 	}
 	
 	 
	 
}