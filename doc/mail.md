发送邮件 
========
配置文件 `config/mail.php`

	
	return  [
		//sendmail smtp  mail,if sendmail /usr/sbin/sendmail -bs
		'type'=>'smtp', 
		'smtp'=>'smtp.teebik-inc.com', 
		'user'=>'tbgames@teebik-inc.com',
		'pwd'=>'FSrW#$DSf',	
		'name'=>'Teebik Games'
	];   

    
使用方法

    	Mail::init()->from()
    	->to(['youaddress@a.com'=>'user'])
    	->title('标题')
    	->body("content内容<hr>不错")
    	->send();

支持方法

	//附件
	attach($file)
	//addPart
	part($html)

官方链接 http://swiftmailer.org/docs/messages.html