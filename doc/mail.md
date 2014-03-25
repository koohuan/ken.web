发送邮件 
========
  
  http://swiftmailer.org/docs/messages.html
   
 

    
使用方法
    	use Ken\Web\Mail;
	//配置
	$smtp = "smtp address";
	$user = 'yourname';
	$pwd = 'yourpassword';
	//或使用
	$smtp = "/usr/sbin/sendmail -bs";


	$mail = new Mail($smtp,$user,$pwd);
	$mail->from(['youraddress@a.com'=>'yourname'])
		->to(['youaddress@a.com'=>'user'])
		->title('标题')
		->attach(file)
		->body("content内容<hr>不错")
		->send();