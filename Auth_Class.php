<?php 
/** 
	登录
	login(\Input::post('username'),\Input::post('password'));
	
	create_full($username,$email,$password)
	
	修改密码
	Auth::update($id,\Input::post('old_password'),$par=['password'=>\Input::post('password')]); 
	
	if(Response::get_code()==200){
		
	}
	
	纯AJAX 方法
	
	$.ajax({
		  type: "POST",
		  url: "/user/create",
		  data:  {'email' : email},
		  success: function (d){ 
			//window.location.reload(); 
		 },
		 error:function(e, txt){  
			alert(e.statusText); 
        }
	});   
	
	ajaxForm 方法
	HTML::code("
    var queryString = $('#form').formSerialize();
    $('#form').ajaxForm({ 
	        error:function(d,txt,e){  
	            alert(e); 
	        }, 
	        success:function(d){  
	            alert('Login success'); 
	        }
	    });
	");


	Common Auth class
	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
 */
namespace Ken\Web; 
class Auth_Class
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
	public $named;
	public $encode = true;
 	static $a = 5,
 	static $b = 20;
 	//快速加密
	function fast($a = 5,$b = 20){
		$this->encode = false;
		static::$a = $a;
		static::$b = $b;
	}
	/**
		判断是否登录
	*/
	function is_login(){
		if(true === $this->cookie){
			return Cookie::get('id')?true:false; 
		}else{
			return Session::get('id')?true:false; 
		} 
	}
	/**
		取得当前用户登录的信息
	*/
	function get(){
		$out = null;
		if(true === $this->cookie){  
			$id = Cookie::get('id');
			$username = Cookie::get('username');
			$email = Cookie::get('email');
			$phone = Cookie::get('phone');
			$uid = Cookie::get('uid'); 
			$method = Cookie::get('method');   
		}else{ 
			$id = Session::get('id');
			$username = Session::get('username');
			$email = Session::get('email');
			$phone = Session::get('phone');
			$uid = Session::get('uid'); 
			$method = Session::get('method');
			if(!$method)
				$method = Cookie::get('method');
		} 
		if($id) $out['id'] = $id;
	 	if($username) $out['username'] = $username;
	 	$out['email'] = $email?:$phone;
	 	if($phone) $out['phone'] = $phone;
	 	if($uid) $out['uid'] = $uid;
	 	if($method) $out['method'] = $method;
	    return $out;
	}
	/**
		设置登录的COOKIE 或 SESSION
	*/
	protected function set($one){ 
		if(true === $this->cookie){
			Cookie::set('id',$one->id,0);
			if($one->username)
				Cookie::set('username',$one->username,0);
			Cookie::set('email',$one->email,0);
			Cookie::set('uid',$one->uid,0);
			Cookie::set('phone',$one->phone,0);
			Cookie::set('method','system',0); 
		}else{
			Session::set('id',$one->id);
			if($one->username)
				Session::set('username',$one->username);
			Session::set('email',$one->email);
			Session::set('phone',$one->phone,0);
			Session::set('method','system',0);
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
	*/
	function login($username , $password){
		if($username && $password) { 
			$cache_id = 'login_'.$username;
			$one = Cache::get($cache_id);
			if($one) goto ENDLOGIN;
		}
		//如果是手机号 
		if(Validate::phone($username)){
			$this->email = 'phone';
		}else{
			if($this->named != 'Auth'){
				Validate::set($this->email,[
						[$this->email,'message'=>__('Must be email address')], 
				],$username); 
				$vali = Validate::message(); 
				if($vali) {
					Response::code(500 , $vali);
					return ['msg'=>$vali];
				} 
			}
		}
		
		$e = [
			__('login fields requied'),
			__('user not exists'),
			__('password error')
		]; 
		if(!$username || !$password) {
			Response::code(500 , $e[0]);
			return $e[0];
		}
		if($this->username!=null){
			$a = $this->username."=? OR ".$this->email."=?";
			$b = [$username,$username];
		}else{
			$a = $this->email."=?";
			$b = [$username];
		} 
		
		if(!$one){
			$one = DB::w()->table($this->table)
				->where($a,$b)
				->one();
			Cache::set($cache_id,$one);
		}
		if(!$one){
			Response::code(500 ,$e[1]);
			return $e[1];
		} 
		ENDLOGIN:
		if(!$this->password_verify($password , $one->password)){
			Response::code(500 ,$e[2]);
			return $e[2];
		}
		Response::code(200); 
		$this->set($one);
		return true; 
	}
	/**
		更新用户，无需密码  
		false 用户不存在 
	*/
	function update_nopwd($id,$par=[]){
		if(!$id) return 1;
		$one = DB::w()->table($this->table)
			->where("id=?",[$id])
			->one();
		if(!$one){
			Response::code(500,__('Update user not exists'));
			return false;
		}
		if($par['password']){
			Validate::set($this->password,[
					['min_length',6,'message'=>__('Password min lenght 6')], 
			],$par['password']);  
			$vali = Validate::message();
			if($vali) {
				Response::code(500 , $vali[0]);
				return ['msg'=>$vali];
			} 
			$par['password'] = $this->passwordHash($par['password']);
			DB::w()->update($this->table,$par,'id=?',[$id]);
		}
		Response::code(200);
		return true;
	}
	/**
		更新用户 
	*/
	function update($id,$old_password,$par=[]){
		$e = [
			__("userID and Old password required"),
			__("user not exists"),
			__("Old password not right")
		];
		if(!$old_password || !$id) {
			Response::code(500 ,$e[0]);
			return  $e[0];
		}
		$one = DB::w()->table($this->table)
			->where("id=?",[$id])
			->one();
		if(!$one){
			Response::code(500 , $e[1]);
			return $e[1];
		} 
		if(!$this->password_verify($old_password , $one->password)){
			Response::code(500 , $e[2]);
			return $e[2];
		}
		if($par['password']){
			Validate::set($this->password,[
					['min_length',6,'message'=>__('Password min lenght 6')], 
			],$par['password']);  
			$vali = Validate::message();
			if($vali) {
				Response::code(500 , $vali[0]);
				return ['msg'=>$vali];
			} 
			
			Response::code(200);
			$par['password'] = $this->passwordHash($par['password']);
			DB::w()->update($this->table,$par,'id=?',[$id]);
		}
		return true;
	}
	/**
	 
		$id = Auth::create($user,$pwd);
		
	*/
	function create($email,$password){
		return $this->_create(null,$email,$password);
	} 
	function create_full($username,$email,$password){
		return $this->_create($username,$email,$password);
	}
	function _create($username=null,$email,$password){
		$e = [
			__('create fields requied'),
			__('user had exists'),
		];
		if(!$email || !$password) {
			Response::code(500 , $e[0]);
			return $e[0];
		}
		//如果是手机号 
		if(Validate::phone($email)){
			$this->email = 'phone';
		}else{
			Validate::set($this->email,[
					[$this->email,'message'=>__('Must be email address')], 
			],$email); 
		}
		$arr = [ 
				$this->email => trim($email),
				'uid' => Str::uid(),
				$this->password => $this->passwordHash(trim($password)),
				$this->create_at => date('Y-m-d H:i:s'), 
			];
		Validate::set($this->password,[
				['min_length',6,'message'=>__('Password min lenght 6')], 
		],$password); 
		
		if($this->username!=null){
			Validate::set($this->username,[
				['alnumu','message'=>'abc num and _'], 
			],$username);  
			$a = $this->username."=? OR ".$this->email."=?";
			$b = [$username,$email];
			$arr[$this->username] = trim($username);
		}else{
			$a = $this->email."=?";
			$b = [$email];
			
		}  
		$vali = Validate::message();
		if($vali) {
			Response::code(500 , $vali[0]);
			return ['msg'=>$vali];
		} 
		
		$one = DB::w()->table($this->table)
			->where($a,$b)
			->one();
		if($one){
			Response::code(500 ,$e[1]);
			return ['msg'=>$e[1]]; 
		} 
		if(!$one){ 
			Response::code(200);
			$id = DB::w()->insert($this->table,$arr);
			$this->login($email,$password);
		}
		return $id; 
	}
 
 
	public function passwordHash($password)
    {
    	if($this->encode===true)
         	return password_hash($password,PASSWORD_BCRYPT); 
        return sha1(substr(md5($password),5,22));
    }
   
    function  password_verify($password,$hash){ 
    	$id = 'password_verify_'.md5($password.$hash);
    	$data = Cache::get($id);
    	
    	if($this->encode===true){ 
    		if(!$data){
	    		$data =  password_verify($password,$hash);
	    		Cache::set($id,$data);
    		}
    		return $data;
    	}
    	
    	return  sha1(substr(md5($password),5,22))==$hash;
    }

    
 
}