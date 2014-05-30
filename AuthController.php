<?php 
/**
　　后台具体权限控制，细化权限
 
	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
 */
namespace Ken\Web;
abstract class AuthController extends AdminController 
{ 
 	public $access; //模块 控制器 动作
 	protected $_access; //权限列表
 	static $public_access;//权限列表,判断菜单显示
 	public $acl = false;//如果为true 将直接不启用细化的权限
 	/**
 	＊　判断具体权限
 	*/
	function init(){
 		 parent::init();  
 		 $this->admin_id = \Auth::get()['id'];
 		 $bind = \DB::w()->from('admin_group_bind')->where('admin_id=?',$this->admin_id)->all(); 
 		 if($bind){
 		 	foreach($bind as $v){
 		 		$g[] = $v->group_id;
 		 	}
 		 }
 		 static::$public_access = $this->_access = $this->get_access_by_group_ids($g);
 		 $this->access = $this->module.'.'.$this->id.'.'.$this->action; 
 	} 
 	/**
 	* 判断菜单是否显示  
 	* if(\AuthController::check_access_menu('content.node.index.'.$v->id.'.r')){
 	* }
 	*/
 	static function check_access_menu($id){   
 		if(!static::$public_access || !in_array($id,static::$public_access) )
 		 		 return false;
 		return true;
 	}
 	/**
 	* 在渲染视图前验证权限
 	*/
 	function view($view,$data = []){ 
 		if($this->acl === false){
	 		if($this->admin_id != 1){  
	 		 	if(!$this->_access || !in_array($this->access,$this->_access) )
	 		 		 throw new \Exception(__('Access Deny!'),500); 
	 		 }
 		}
 		 parent::view($view,$data);
 	}
 	/**
	* 返回用户组的权限列表。
	* 组ID可以为数组
	*/
	function get_access_by_group_ids($gid){
		if(!$gid) return [];
		if(!is_array($gid)) $gid = [$gid];
		$posts = \DB::w()->from('admin_group_access')->where('group_id in ('.\DB::in($gid).')',$gid)->all(); 
  		if($posts){
  			foreach($posts as $p){
  				$find[] = $p->access_id;
  			}
  			$access = \DB::w()->from('admin_access')->where('id in ('.\DB::in($find).')',$find)->all(); 
  			if($access){
  				foreach($access as $v){
  					$ac[] = $v->access;
  				}
  			} 
  		}
  		return $ac?:[];  		
	}
	// 由用户的ID取用户组信息,显示在管理员中的用户列表中的组信息
   	static function get_group_by_user_id($user_id){ 
	 	$bind = \DB::w()->from('admin_group_bind')->where('admin_id=?',[$user_id])->all(); 
 	 	if($bind){
 	 		foreach($bind as $v){ 
 	 			$group_id[] = $v->group_id;
 	 		} 
 	 	} 
 	 	if(!$group_id) return null;
 	 	$all = \DB::w()->from('admin_group')->where('id in ('.\DB::in($group_id).')',$group_id)->all(); 
 	 	if($all){
 	 		foreach($all as $v){
 	 			$group[] = $v->name;
 	 		}
 	 	}
 	 	return $group;
	}
	
	 
	
	 
 
	
 
}