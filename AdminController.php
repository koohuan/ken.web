<?php 
/**
	普通管理员，没登录就跳至登录页。　
    并没有具体权限控制　
 
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
  	protected $admin_id;//管理员ID
 	/**
 		判断是否有权限
 	*/
	function init(){
 		 parent::init(); 
 		 if( $this->allow && in_array($this->module.'.'.$this->id.'.'.$this->action,$this->allow )){
 		 	goto NEXT;
 		 }
 		 if(true !== Auth::is_login()){
 		 	$this->redirect(url($this->login_url));
 		 	
 		 }
 		 $this->auth = Auth::get(); 
 	 	 NEXT:
 	 	 $this->db = DB::w(); 
 	}
	
	 
 
	
 
}