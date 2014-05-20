管理员权限
========
登录

	Auth::login(\Input::post('username'),\Input::post('password'));
 
修改密码

	$t = Auth::update($id,\Input::post('old_password'),$par=['password'=>\Input::post('password')]);
	if(true === $t){
		\Session::flash('success','change password success!');
	 	$this->redirect(url('admin/user/index'));
	}else{
		$error =  'old password is failed!'; 
	}


创建管理员

	$id = Auth::create(
			\Input::post('username'),
			\Input::post('email'),
			\Input::post('password'));
	
	$id = Auth::create_email(\Input::post('email'),\Input::post('password'));



如果使用`jquery`


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
