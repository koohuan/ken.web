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
 	public $allow = [];
 	public $allow_guest = false;
 	/**
 		判断是否有权限
 	*/
	function init(){
 		 parent::init();
 		 $this->auth = F::get('auth'); 
 		 if(true === $this->allow_guest) goto NEXT;
 		 if( $this->allow && in_array($this->id,$this->allow )){
 		 	
 		 }
 		 if(!$this->auth->is_logined()){
 		 	$this->redirect(url('admin/login/index'));
 		 	
 		 }
 		 $this->auth->logined = $this->auth->get(); 
 		 NEXT:
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