<?php 
/**
	
	依赖 Controller.php  
	
	F::set('auth',function(){ 
		return new Auth;
	});
	
    会员控制器
    
    该控制器依然为非必须的
 
	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
 */
namespace Ken\Web; 
abstract class UserController extends Controller 
{ 
 	protected $auth;
 	//默认使用 public/themes/admin 
 	public $theme = 'default';
 	/**
 		判断是否有权限
 	*/
	function __construct(){
 		 parent::__construct();
 		 $this->auth = F::get('user');
 		 //以COOKIE形式保存数据
 		 $this->auth->cookie = true;
 		 $this->auth->table = 'users';
 		 if(!$this->auth->is_logined()){
 		 	throw new \Exception('Access Deny',403);
 		 }
 		 $this->auth->logined = $this->auth->get();  
 	}
	
 
	
 
}