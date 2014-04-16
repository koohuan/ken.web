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
 		 if( $this->allow && in_array($this->module.'.'.$this->id.'.'.$this->action,$this->allow )){
 		 	goto NEXT;
 		 }
 		 if(true !== Auth::is_logined()){
 		 	$this->redirect(url($this->login_url));
 		 	
 		 }
 		 $this->auth = Auth::get(); 
 	 	 NEXT:
 	 	 $this->db = DB::w(); 
 	}
	
	 
 
	
 
}