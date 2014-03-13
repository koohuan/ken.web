<?php 
/**
    用户权限
 	依赖 F.php  DB.php Cookie.php Session.php
 	
 	$db = [
		["mysql:dbname=wei;host=127.0.0.1","test","test"],
		["mysql:dbname=wei2;host=127.0.0.1","test","test"],
	];
 	F::set('db',function() use ($db){
		$config = $db[0];
		return new DB($config[0],$config[1],$config[2]);  
	});
 	
 	
 	CREATE TABLE IF NOT EXISTS `admin` (
	  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  `username` varchar(50) NOT NULL,
	  `password` varchar(64) NOT NULL,
	  `email` varchar(50) NOT NULL,
	  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	  `update_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
	

	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
 */
namespace Ken\Web; 
class Auth
{ 
	public $table = 'admin';
	//是否使用COOKIE，默认使用SESSION
	public $cookie = false;
	//数据库各字段设置
	public $username = 'username';
	public $password = 'password';
	public $email = 'email';
	public $create_at = 'create_at';
	public $update_at = 'update_at';
	public $logined;
	/**
		判断是否登录
	*/
	function is_logined(){
		if(true === $this->cookie){
			return Cookie::get('id'); 
		}else{
			return Session::get('id'); 
		} 
	}
	/**
		取得当前用户登录的信息
	*/
	function get(){
		if(true === $this->cookie){  
			return [
				'id'=>Cookie::get('id'),
				'username'=>Cookie::get('username'),
				'email'=>Cookie::get('email'),
				'uid'=>Cookie::get('uid'),
			];
		}else{
			return [
				'id'=>Session::get('id'),
				'username'=>Session::get('username'),
				'email'=>Session::get('email'),
				'uid'=>Session::get('uid'),
			];
		} 
	}
	/**
		设置登录的COOKIE 或 SESSION
	*/
	protected function set($one){
		if(true === $this->cookie){
			Cookie::set('id',$one->id,0);
			Cookie::set('username',$one->username,0);
			Cookie::set('email',$one->email,0);
			Cookie::set('uid',$one->uid,0);
		}else{
			Session::set('id',$one->id);
			Session::set('username',$one->username);
			Session::set('email',$one->email);
			Session::set('uid',$one->uid);
		}
		
	} 
	/**
		安全退出
	*/
	function logout(){
		if(true === $this->cookie){
			Cookie::delete('id');
			Cookie::delete('username');
			Cookie::delete('email');
			Cookie::delete('uid');
		}else{
			Session::delete('id');
			Session::delete('username');
			Session::delete('email');
			Session::delete('uid');
		}
	}
	/**
		登录
		1 not exists username;
		2 password is error
	*/
	function login($username , $password){
		if($this->username!=null){
			$a = $this->username."=? OR ".$this->email."=?";
			$b = [$username,$username];
		}else{
			$a = $this->email."=?";
			$b = [$username];
		} 
		$one = F::get('db')->table($this->table)
			->where($a,$b)
			->one();
		if(!$one){
			return 1;
		}
		if(!static::validatePassword($password , $one->password)){
			return 2;
		}
		$this->set($one);
		return true; 
	}
	/**
		更新用户，无需密码  
		false 用户不存在 
	*/
	function update_nopwd($id,$par=[]){
		if(!$id) return 1;
		$one = F::get('db')->table($this->table)
			->where("id=?",[$id])
			->one();
		if(!$one){
			return false;
		}
		if($par['password']){
			$par['password'] = static::passwordHash($par['password']);
			F::get('db')->update($this->table,$par,'id=?',[$id]);
		}
		return true;
	}
	/**
		更新用户
		
		1 原密码不能为空
		2 用户不存在
		3 原密码是错误的
	*/
	function update($id,$old_password,$par=[]){
		if(!$old_password || !$id) return 1;
		$one = F::get('db')->table($this->table)
			->where("id=?",[$id])
			->one();
		if(!$one){
			return 2;
		} 
		if(!static::validatePassword($old_password , $one->password)){
			return 3;
		}
		if($par['password']){
			$par['password'] = static::passwordHash($par['password']);
			F::get('db')->update($this->table,$par,'id=?',[$id]);
		}
		return true;
	}
	/**
		创建用户
		如果是 false 说明 用户已存在
	*/
	function create($username=null,$email,$password){
		$arr = [ 
				$this->email => trim($email),
				'uid' => Str::uuid(),
				$this->password => static::passwordHash(trim($password)),
				$this->create_at => date('Y-m-d H:i:s'), 
			];
		if($this->username!=null){
			$a = $this->username."=? OR ".$this->email."=?";
			$b = [$username,$username];
			$arr[$this->username] = trim($username);
		}else{
			$a = $this->email."=?";
			$b = [$email];
			
		} 
		$one = F::get('db')->table($this->table)
			->where($a,$b)
			->one();
		if($one){
			return false; 
		}
		if(!$one){ 
			$id = F::get('db')->insert($this->table,$arr);
		}
		return $id; 
	}
	/**
		以下代码来源yii2.0
		yii2.0	yii\helpers\BaseSecurity
	*/
	public static function passwordHash($password, $cost = 13)
    {
        $salt = static::generateSalt($cost);
        $hash = crypt($password, $salt); 
        if (!is_string($hash) || strlen($hash) < 32) {
                throw new \Exception('Unknown error occurred while generating hash.');
        } 
        return $hash;
    }
  	public static function validatePassword($password, $hash)
    {
            if (!is_string($password) || $password === '') {
                    throw new \Exception('Password must be a string and cannot be empty.');
            }

            if (!preg_match('/^\$2[axy]\$(\d\d)\$[\.\/0-9A-Za-z]{22}/', $hash, $matches) || $matches[1] < 4 || $matches[1] > 30) {
                    throw new \Exception('Hash is invalid.');
            }

            $test = crypt($password, $hash);
            $n = strlen($test);
            if ($n < 32 || $n !== strlen($hash)) {
                    return false;
            }

            // Use a for-loop to compare two strings to prevent timing attacks. See:
            // http://codereview.stackexchange.com/questions/13512
            $check = 0;
            for ($i = 0; $i < $n; ++$i) {
                    $check |= (ord($test[$i]) ^ ord($hash[$i]));
            }

            return $check === 0;
    }

    /**
     * Generates a salt that can be used to generate a password hash.
     *
     * The PHP [crypt()](http://php.net/manual/en/function.crypt.php) built-in function
     * requires, for the Blowfish hash algorithm, a salt string in a specific format:
     * "$2a$", "$2x$" or "$2y$", a two digit cost parameter, "$", and 22 characters
     * from the alphabet "./0-9A-Za-z".
     *
     * @param integer $cost the cost parameter
     * @return string the random salt value.
     * @throws InvalidParamException if the cost parameter is not between 4 and 31
     */
    protected static function generateSalt($cost = 13)
    {
            $cost = (int)$cost;
            if ($cost < 4 || $cost > 31) {
                    throw new InvalidParamException('Cost must be between 4 and 31.');
            }

            // Get 20 * 8bits of pseudo-random entropy from mt_rand().
            $rand = '';
            for ($i = 0; $i < 20; ++$i) {
                    $rand .= chr(mt_rand(0, 255));
            }

            // Add the microtime for a little more entropy.
            $rand .= microtime();
            // Mix the bits cryptographically into a 20-byte binary string.
            $rand = sha1($rand, true);
            // Form the prefix that specifies Blowfish algorithm and cost parameter.
            $salt = sprintf("$2y$%02d$", $cost);
            // Append the random salt data in the required base64 format.
            $salt .= str_replace('+', '.', substr(base64_encode($rand), 0, 22));
            return $salt;
    }
    
 
}