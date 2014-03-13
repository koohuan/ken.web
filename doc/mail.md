发送邮件 
========
  
官方 http://swiftmailer.org/docs/messages.html
   
   composer.json 的 require 需要包含
  
	"swiftmailer/swiftmailer": "*"  

    
使用方法
    
	$smtp = "smtp address";
	$user = 'yourname';
	$pwd = 'yourpassword';
	$mail = new \Mail($smtp,$user,$pwd);
	$mail->from(['youraddress@a.com'=>'yourname'])
		->to(['youaddress@a.com'=>'user'])
		->title('标题')
		->attach(file)
		->body("content内容<hr>不错")
		->send();