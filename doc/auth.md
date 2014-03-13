管理员权限
========
登录

	$id = \F::get('auth')->login(\Input::post('username'),\Input::post('password'));
 
修改密码

	$t = \F::get('auth')->update($id,\Input::post('old_password'),$par=['password'=>\Input::post('password')]);
	if(true === $t){
		\Session::flash('success','change password success!');
	 	$this->redirect(url('admin/user/index'));
	}else{
		$error =  'old password is failed!'; 
	}


创建管理员

	$id = \F::get('auth')->create(
			\Input::post('username'),
			\Input::post('email'),
			\Input::post('password'));
