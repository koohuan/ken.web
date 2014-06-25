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
	public $account = 'email';
	public $mi  = false;//email phone 这2个字段都支持

	/**
	* 判断当前字段是email 还是 phone 
	*/
	function email_phone($email){
		if(Validate::email($email))
			$this->account = 'email';
		else if(Validate::phone($email)){
			$this->account = 'phone'; 
			$this->mi = true;
		}
	}
	function is_guest(){
		if(true === $this->cookie){
			return Cookie::get('id')?false:true; 
		}else{
			return Session::get('id')?false:true; 
		}  
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
			$obj = (object)Cookie::get();
			
		}else{ 
			$obj = (object)Session::get(); 
		}  
		$obj->account = $obj->email;
		if(true === $this->mi){
			$obj->account = $obj->phone; 
		}
		return $obj;
	}
	/**
		设置登录的COOKIE 或 SESSION
	*/
	protected function set($one){ 
		$this->logout();
		$data['id'] = $one->id;
		$data['email'] = $one->email;
		if(true === $this->mi)
			$data['phone'] = $one->phone;
		$data['method']='self';
		$data['name'] = $one->phone?:$one->email;
		if(true === $this->cookie){ 
			Cookie::set($data); 
		}else{
			Session::set($data); 
		} 
	} 
	/**
		安全退出
	*/
	function logout(){
		$array = ['id','name','method','phone','email'];
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
		
		$e = [
			__('login fields requied'),
			__('user not exists'),
			__('password error')
		]; 
		if(!$email || !$password) {
			return ['code'=>500,'msg'=>$e[0]]; 
		} 
		$this->email_phone($email);
		$cache_id = 'login_'.$email;
		$one = Cache::get($cache_id);
		if($one) goto ENDLOGIN; 

		if(!$one){
			$one = DB::w()->table($this->table)
				->where($this->account."=?",$email)
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
		//清除cache
		$this->clear_cache($one);
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

	function clear_cache($one){
		//清除cache
		Cache::delete($one->email);
		if(true === $this->mi)
			Cache::delete($one->phone);
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
		//清除cache
		$this->clear_cache($one);
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
	function create($email,$password,$pars = []){
		$e = [
			__('字段必须'),
			__('用户已存在'),
		];
		if(!$email || !$password) {
			return ['code'=>500,'msg'=>$e[0]];  
		} 
		$this->email_phone($email);
		$arr = [ 
			$this->account => trim($email), 
			'password' => $this->passwordHash(trim($password)),
			'create_at' => date('Y-m-d H:i:s'), 
		];
		if($pars) $arr = $arr+$pars;
		Validate::set('password',[
				['min_length',6,'message'=>__('密码至少6位！')], 
		],$password); 
		
	
		$vali = Validate::message();
		if($vali) {
			return ['code'=>500,'msg'=>$vali[0]];  
		} 
		 
		$one = DB::w()->table($this->table)
			->where($this->account."=?",$email)
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
        return sha1(md5($password));
    }
   
    function  password_verify($password,$hash){  
    	return  sha1(md5($password))==$hash;
    }

    
 
}