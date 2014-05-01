<?php 
/**
   http://swiftmailer.org/docs/messages.html
  
    使用方法
    
  
  
    Mail::init()->from(['youraddress@a.com'=>'yourname'])
    	->to(['youaddress@a.com'=>'user'])
    	->title('标题')
    	->body("content内容<hr>不错")
    	->send();
    
    
 	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
*/
namespace Ken\Web\Vendor;
use Ken\Web\Config;
class Swift{
	public $transport;
	public $message;
	public $mailer;
	static $obj;
	static function init(){
		if(!isset(static::$obj))
			static::$obj = new Static;
		return static::$obj;
	}
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
	
	function __construct() {
		import(__DIR__.'/Swift/swift_required.php'); 
		$type = Config::get('mail.type');
		switch($type){
			case "smtp":
				$this->smtp(Config::get('mail.smtp'),Config::get('mail.user'),Config::get('mail.pwd'));
				break;
			case "sendmail":
				$this->sendmail(Config::get('mail.smtp'));
				break;
			case "mail":
				$this->mail();
				break;
		} 
		$this->mailer = \Swift_Mailer::newInstance($this->transport); 
		$this->message = \Swift_Message::newInstance(); 
		return $this;
	}
	
	
	function title($title){
		$this->message->setSubject($title);
		return $this;
	}
	/**
		['john@doe.com' => 'John Doe']
	*/
	function from($arr = null){
		if(!$arr){
			$arr = [Config::get('mail.user')=>Config::get('mail.name')?:'mailer'];
		}
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