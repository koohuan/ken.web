<?php 
/** 
	登录
	login(\Input::post('username'),\Input::post('password'));
	
	create($username,$email,$password)
	
	修改密码
	Auth::update($id,\Input::post('old_password'),$par=['password'=>\Input::post('password')]); 
 	

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
	public $logined;   
	public $uid = false;
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
			return Cookie::get();
			
		}else{ 
			return Session::get(); 
		}  
	}
	/**
		设置登录的COOKIE 或 SESSION
	*/
	protected function set($one){ 
		if(true === $this->cookie){ 
			Cookie::set($one); 
		}else{
			Session::set($one); 
		} 
	} 
	/**
		安全退出
	*/
	function logout(){
		$array = ['id','email','uid'];
		if(true === $this->cookie){
			Cookie::delete($array); 
		}else{
			Session::delete($array); 
		}
	}
	/**
		登录 
	*/
	function login($email , $password){
		if($username && $password) { 
			$cache_id = 'login_'.$username;
			$one = Cache::get($cache_id);
			if($one) goto ENDLOGIN;
		} 
		$e = [
			__('login fields requied'),
			__('user not exists'),
			__('password error')
		]; 
		if(!$email || !$password) {
			return ['code'=>500,'msg'=>$e[0]]; 
		} 
		if(!$one){
			$one = DB::w()->table($this->table)
				->where("email=?",$email)
				->one();
			Cache::set($cache_id,$one);
		}
		if(!$one){
			return ['code'=>500,'msg'=>$e[1]];  
		} 
		ENDLOGIN:
		if(!$this->password_verify($password , $one->password)){
			return ['code'=>500,'msg'=>$e[2]];  
		} 
		unset($one->create_at,$one->update_at);
		$this->set($one);
		return ['code'=>0,'msg'=>'OK'];  
	}
	/**
		更新用户，无需密码  
		false 用户不存在 
	*/
	function update_nopwd($id,$par=[]){
		if(!$id) return 1;
		$one = DB::w()->table($this->table)
			->where("id=?",$id)
			->one();
		if(!$one){
			return ['code'=>500,'msg'=>__('Update user not exists')]; 
		}
		if($par['password']){
			Validate::set($this->password,[
					['min_length',6,'message'=>__('Password min lenght 6')], 
			],$par['password']);  
			$vali = Validate::message();
			if($vali) {
				return ['code'=>500,'msg'=>$vali[0]]; 
			} 
			$par['password'] = $this->passwordHash($par['password']);
			DB::w()->update($this->table,$par,'id=?',$id);
		}
		return ['code'=>0,'msg'=>'OK']; 
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
			return ['code'=>500,'msg'=>$e[0]]; 
		}
		$one = DB::w()->table($this->table)
			->where("id=?",[$id])
			->one();
		if(!$one){
			return ['code'=>500,'msg'=>$e[1]]; 
		} 
		if(!$this->password_verify($old_password , $one->password)){
			return ['code'=>500,'msg'=>$e[2]]; 
		}
		if($par['password']){
			Validate::set($this->password,[
					['min_length',6,'message'=>__('Password min lenght 6')], 
			],$par['password']);  
			$vali = Validate::message();
			if($vali) {
				return ['code'=>500,'msg'=>$vali[0]]; 
			} 
			$par['password'] = $this->passwordHash($par['password']);
			DB::w()->update($this->table,$par,'id=?',$id);
		}
		return ['code'=>0,'msg'=>'OK']; 
	}
	function create($email,$password){
		$e = [
			__('create fields requied'),
			__('user had exists'),
		];
		if(!$email || !$password) {
			return ['code'=>500,'msg'=>$e[0]];  
		} 
		$arr = [ 
			'email' => trim($email), 
			'password' => $this->passwordHash(trim($password)),
			'create_at' => date('Y-m-d H:i:s'), 
		];
		if($this->uid){
			$arr['uid'] = Str::id();
		}
		Validate::set('password',[
				['min_length',6,'message'=>__('Password min lenght 6')], 
		],$password); 
		
	
		$vali = Validate::message();
		if($vali) {
			return ['code'=>500,'msg'=>$vali[0]];  
		} 
		
		$one = DB::w()->table($this->table)
			->where("email=?",$email)
			->one();
		if($one){
			return ['code'=>500,'msg'=>$e[1]];  
		} 
		if(!$one){  
			$id = DB::w()->insert($this->table,$arr);
			$this->login($email,$password);
		}
		return ['code'=>0,'msg'=>'OK','id'=>$id];  
	}
 
 
	public function passwordHash($password)
    { 
        return sha1(substr(md5($password),5,22));
    }
   
    function  password_verify($password,$hash){  
    	return  sha1(substr(md5($password),5,22))==$hash;
    }

    
 
}