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
 	 
 	/**
 	＊　判断具体权限
 	*/
	function init(){
 		 parent::init();  
 		 $user_id = \Auth::get()['id'];
 		 $bind = \DB::w()->from('admin_group_bind')->where('admin_id=?',$user_id)->all(); 
 		 dump($bind);
 	} 
 	/**
	* 返回用户组的权限列表。
	* 组ID可以为数组
	*/
	function get_access_by_group_ids($gid){
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
 	 	$all = \DB::w()->from('admin_group')->where('id in ('.\DB::in($group_id).')',$group_id)->all(); 
 	 	if($all){
 	 		foreach($all as $v){
 	 			$group[] = $v->name;
 	 		}
 	 	}
 	 	return $group;
	}
	
	 
	
	 
 
	
 
}