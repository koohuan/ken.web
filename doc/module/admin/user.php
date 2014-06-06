<?php
/**
 	用户管理
 	
	@auth Kang Sun <68103403@qq.com>
 	@date 2014
	
*/
namespace Ken\Web\doc\module\admin;  
class user extends \AuthController{ 
  	function init(){
  		parent::init();
  		\Menu::set('admin.user');
  	}
  	
 	function indexAction(){  
 	 	$posts = \DB::w()->from('admin')->all(); 
 		$this->view('user',['posts'=>$posts,'group'=>$group]);
 	}
 	
 	function groupAction(){ 
 		$id = $_GET['id'];
 		$bind = \DB::w()->from('admin_group_bind')->where('admin_id=?',[$id])->all(); 
 		$group = $_POST['group'];
 		if($group){
 			foreach($group as $v){
 				$data[] = [
 					'admin_id'=>$id,
 					'group_id'=>$v
 				];
 			} 
 			\DB::w()->delete('admin_group_bind','admin_id=?',[$id]); 
 			\DB::w()->insert_batch('admin_group_bind',$data);
 			\Session::flash('success',__('Bind Success')); 
			$this->redirect(url('admin/user/index'));
 		}
 	 	$group = \DB::w()->from('admin_group')->all();
 	 	$user = \DB::w()->from('admin')->pk($id);
 	 	 
 	 	if($bind){
 	 		foreach($bind as $v){
 	 			$has[] = $v->group_id;
 	 		}
 	 	 
 	 	}
 		$this->view('user_group',['user'=>$user,'group'=>$group,'has'=>$has]);
 	}
 	
 	
 	function deleteAction(){
 		$id = $_GET['id'];
 		if($id != 1){
 			\DB::w()->delete('admin','id=?',$id); 
 			\DB::w()->delete('admin_group_bind','admin_id=?',$id); 
 			\DB::w()->delete('admin_group_bind','admin_id=?',$id); 
 			\Session::flash('success',__('Delete User Success')); 
			$this->redirect(url('admin/user/index'));
 		}
 	}
  	/**
	* 更新用户	 
	*/ 
	function updateAction(){   
		$id = $_GET['id'];
		$one = \DB::w()->from('admin')->pk($id);
		$element = [ 
		 	'old_password'=>[
		 		'label'=>'Old Password',
		 		'element'=>'password',
		 	],
		 	'password'=>[
		 		'label'=>'New Password',
		 		'element'=>'password',
		 	],
		
		];
		$button = "save";
		if($_POST){
			\Validate::set('old_password',[
				['not_empty','message'=>__('not empty')], 
			]);
			\Validate::set('password',[
				['not_empty','message'=>__('not empty')], 
			]);
		 
			if(\Validate::run()){  
		 		$id = \Auth::update($id,\Input::post('old_password'),['password'=>\Input::post('password')]); 
			 	if(\Response::get_code() == 200 ) {
			 	 	\Session::flash('success',__('Update Password Success')); 
				 	$this->redirect(url('admin/admin/index'));
			 	}else{ 
				 	$error = \Arr::to_str($id);
			 	}
			} 
			 
	 	} 
	  	$vali = \Validate::message();
	 	if($vali) $error  = $vali[0];  
	 	$this->view('update_user_form',['one'=>$one,'error'=>$error,'element'=>$element,'button'=>$button]);
	 	
	}
 	
	/**
	* 创建用户	 
	*/ 
	function addAction(){   
		$element = [
			'username'=>[
		 		'label'=>'username',
		 		'element'=>'input',
		 	],
		 	'email'=>[
		 		'label'=>'email',
		 		'element'=>'input',
		 	],
		 	'password'=>[
		 		'label'=>'password',
		 		'element'=>'password',
		 	],
		
		];
		$button = "save";
		if($_POST){
			\Validate::set('username',[
				['not_empty','message'=>__('not empty')], 
			]);
			\Validate::set('email',[
				['not_empty','message'=>__('not empty')],
				['email','message'=>__('should be email address')] 
			]);
			\Validate::set('password',[
				['not_empty','message'=>__('not empty')], 
			]);  
			if(\Validate::run()){  
		 		$id = \Auth::create_full(\Input::post('username'),\Input::post('email'),\Input::post('password')); 
			 	if(\Response::get_code() == 200 ) {
			 	 	\Session::flash('success',__('Login Success')); 
				 	$this->redirect(url('admin/admin/index'));
			 	}else{ 
				 	$error = \Arr::to_str($id);
			 	}
			} 
			 
	 	} 
	  	$vali = \Validate::message();
	 	if($vali) $error  = $vali[0];  
	 	$this->view('form',['error'=>$error,'element'=>$element,'button'=>$button,'title'=>__('User')]);
	 	
	}
	 
}