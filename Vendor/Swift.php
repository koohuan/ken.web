<?php 
/**
   http://swiftmailer.org/docs/messages.html
  
    使用方法
    
    $smtp = "smtp.163.com";
	$user = 'yourname';
	$pwd = 'yourpassword';
 	$mail = new \Mail($smtp,$user,$pwd);
    $mail->from(['youraddress@a.com'=>'yourname'])
    	->to(['youaddress@a.com'=>'user'])
    	->title('标题')
    	->body("content内容<hr>不错")
    	->send();
    
    
 	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
*/
namespace Ken\Web\Vendor;
class Swift{
	public $transport;
	public $message;
	public $mailer;
	function smtp($smtp,$user,$pwd,$port=25){
		$this->transport = \Swift_SmtpTransport::newInstance($smtp, $port)
			  ->setUsername($user)
			  ->setPassword($pwd);
 	}
	
	function sendmail($bin = '/usr/sbin/sendmail -bs'){ 
		$this->transport = \Swift_SendmailTransport::newInstance($bin);
 	}
	
	function mail(){
		$this->transport = \Swift_MailTransport::newInstance();
 	}
	
	function send(){
	 
		if(!$this->mailer->send($this->message)){
			echo 'send mail failed';
			return false;
		}
		return true;
 	} 
	
	function __construct($smtp=null,$user=null,$pwd=null) {
		import(__DIR__.'/Swift/swift_required.php'); 
		if($pwd) { $this->smtp($smtp,$user,$pwd);}
		else if(strpos($smtp,'sendmail')!==false){ $this->sendmail($smtp);}
		else $this->mail();
		$this->mailer = \Swift_Mailer::newInstance($this->transport); 
		$this->message = \Swift_Message::newInstance(); 
	}
	
	function title($title){
		$this->message->setSubject($title);
		return $this;
	}
	/**
		['john@doe.com' => 'John Doe']
	*/
	function from($arr = []){
		$this->message->setFrom($arr) ;
		return $this;
	}
	function to($arr = []){
		$this->message->setTo($arr);
		return $this;
		return $this;
 	}
	function body($body){
		$this->message->setBody($body, 'text/html');
		return $this;
 	}
	function part($body){
		$this->message->addPart($body, 'text/html'); 
		return $this;
 	}
 	
	function attach($file){
		$this->message->attach(Swift_Attachment::fromPath($file));
		return $this;
	}
 
}