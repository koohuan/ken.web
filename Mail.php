<?php 
/**
   http://swiftmailer.org/docs/messages.html
  
    使用方法
    
     Mail::init()->from()
    	->to(['youaddress@a.com'=>'user'])
    	->title('标题')
    	->body("content内容<hr>不错")
    	->send();
    
    
 	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
*/
namespace Ken\Web;
class Mail extends \Ken\Web\Vendor\Swift{}