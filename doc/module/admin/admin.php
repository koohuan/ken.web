<?php
/**
 	admin
	@auth Kang Sun <68103403@qq.com>
 	@date 2014
	
*/
namespace Ken\Web\doc\module\admin;  
class admin extends \AuthController{ 
 	public $allow = ['admin.admin.login'];
 	public $acl = true;
 	function indexAction(){
 		 
 		$this->view('index');
 	}
 	function logoutAction(){
 		\Auth::logout();
 		\Session::flash('success',__('Logout Success'));
		$this->redirect(url('admin/admin/login'));
 	}
 	
	/**
		 
	*/ 
	function loginAction(){  
		$this->title = 'login';
		$element = [
			'username'=>[
		 		'label'=>'username',
		 		'element'=>'input',
		 	],
		 	'password'=>[
		 		'label'=>'password',
		 		'element'=>'password',
		 	],
		
		];
		$button = "login";
		if($_POST){
			\Validate::set('username',[
				['not_empty','message'=>'not empty'], 
			]);
			\Validate::set('password',[
				['not_empty','message'=>'not empty'], 
			]);  
			if(\Validate::run()){  
		 		$login = \Auth::login(\Input::post('username'),\Input::post('password'));
		 	 
			 	if( \Response::get_code() ==200 ) {
			 	 	\Session::flash('success',__('Login Success')); 
				 	$this->redirect(url('admin/admin/index'));
			 	}else{
				 	$error = \Arr::to_str($login);
			 	}
			} 
	 	} 
	  	$vali = \Validate::message(); 
	 	if($vali) $error  = $vali[0];    
	 	$this->view('login',['error'=>$error,'element'=>$element,'button'=>$button]);
	 	
	}
	 
}