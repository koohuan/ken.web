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
 	// ['admin.admin.login']
 	public $allow = []; 
  	public $db;
  	public $login_url = 'admin/admin/login';
 	/**
 		判断是否有权限
 	*/
	function init(){
 		 parent::init();
 		 $this->auth = F::get('auth'); 
 	 
 		 if( $this->allow && in_array($this->module.'.'.$this->id.'.'.$this->action,$this->allow )){
 		 	goto NEXT;
 		 }
 		 if(!$this->auth->is_logined()){
 		 	$this->redirect(url($this->login_url));
 		 	
 		 }
 		 $this->auth->logined = $this->auth->get(); 
 	 	 NEXT:
 	 	 $this->db = \F::get('db');
 		 $this->_boot();
 	}
	
	/**
		bootstrap.php
	*/
	protected function _boot(){
		$route = F::get('route');
		$dir = base_path().'/'.$route::$r.'/';
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