<?php 
/**
	依赖 Controller.php  
	
	F::set('auth',function(){ 
		return new Auth;
	});
	
    管理员权限控制器
    
    该控制器依然为非必须的
 
	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
 */
namespace Ken\Web;
abstract class AdminController extends Controller 
{ 
 	protected $auth;
 	//默认使用 public/themes/admin 
 	public $theme = 'admin';
 	/**
 		判断是否有权限
 	*/
	function __construct(){
 		 parent::__construct();
 		 $this->auth = F::get('auth');
 		 if(!$this->auth->is_logined()){
 		 	$this->redirect(url('admin/login/index'));
 		 	
 		 }
 		 $this->auth->logined = $this->auth->get(); 
 		 $this->_boot();
 	}
	
	/**
		bootstrap.php
	*/
	protected function _boot(){
		$dir = base_path().'/app/';
		$list = scandir($dir);
		foreach($list as $v){ 
			if($v== '.gitignore' || $v=='.' || $v=='..' || $v=='.svn' || $v=='.git') continue; 
			if(is_dir($dir.$v)){
				$boot = $dir.$v.'/bootstrap.php';
				if(file_exists($boot))
					include $boot;
			} 
		}
	}
 
	
 
}