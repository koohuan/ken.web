<?php
/**
 	用户组管理
 	
	@auth Kang Sun <68103403@qq.com>
 	@date 2014
	
*/
namespace Ken\Web\doc\module\admin;  
class group extends \AuthController{ 
	public $ext = ['admin','content','query'];
  	function init(){
  		parent::init();
  		\Menu::set('admin.user');
  	}
  	//生成权限
  	protected function _access(){
  		$post = \DB::w()->from('node_table')->all(); 
  		if($post){
  			foreach($post as $v){ 
  				$id = "content.node.index.".$v->id;
  				$name = $v->name;
  				$data[$id] = $name;
  			}
  		}
  		$dir = base_path().'/module/';
  		$list = scandir($dir);
  		foreach($list as $v){
  			if($v != '.' && $v != '..' && !in_array($v,$this->ext)){
  				$module_name = $v;
  				$d = $dir.$v;
  				$li = scandir($d);
  				foreach($li as $vo){
  					if($v != '.' && $v != '..' && substr($vo,-4) == '.php'){
  						$file = $d.'/'.$vo;
  						$class = substr($vo,0,-4);
  						$line = @file_get_contents($file);  
						preg_match_all('/.*class.*extends(.*)/i',$line,$out);     
						if(false!==strpos($out[1][0],'\AuthController')) { 
							 $new_dirs[$module_name.'.'.$class] = $file; 
							 $i++;
						}  		
  					}
  				} 
  			}
  		}  
  		if($new_dirs){
			foreach($new_dirs as $k=>$dir){ 
				$lineNumber = 0; 
				$file = fopen($dir, 'r'); 
				while( feof($file)===false )
				{ 
					++$lineNumber;
					$line = trim(fgets($file));
					preg_match('/function[\t| ]+([a-zA-z0-9]+Action).*/i', $line, $matches);
					if( $matches!==array() )
					{ 
						$name = $matches[1];
						$name = str_replace('Action','',$name);
						$name = strtolower($name);   
						$ar = array_slice(file($dir),$lineNumber-2,1);
						if($ar && $ar[0]){
							$txt = trim(substr(trim($ar[0]),2));
						}
						$actions[$k.'.'.$name ] = array(
							'name'=>$name,
							'txt'=>$txt,
							'line'=>$lineNumber
						); 
					}
				}
			} 
		}
		return ['data'=>$data,'actions'=>$actions];
  	}
  	//权限
  	function accessAction(){
  		$post = \DB::w()->from('admin_group')->where('id=?',\Input::get('id'))->one(); 
  		$access =  $this->_access();
  		$gid = $_GET['id'];
  		$ac = $this->get_access_by_group_ids($gid); 
  		if($_POST['c']){
  		  	 $all = \DB::w()->from('admin_access')->all();
  		  	 $data = [];
  		  	 if($all){
  		  	 	foreach($all as $v){
  		  	 		$data[] = $v->access;
  		  	 	}
  		  	 }
  		  	 if($data){ 
  		  		 $data = array_diff($_POST['c'],$data);
  		  	 }else{
  		  	 	 $data = $_POST['c']; 
   		     } 
  		   	 if($data){
  		   	    foreach($data as $v){
  		   	    	$batch[] = ['access'=>$v];
  		   	    }
				\DB::w()->insert_batch('admin_access',$batch);  
			 }
			 //写入权限对应组的关联表
			 unset($batch);
			 $in = $_POST['c']; 
			 $all = \DB::w()->from('admin_access')->where('access in ('.\DB::in($in).')',$in)->all();  
			 if($all){
			 	foreach($all as $v){
			 		$batch[] = [
			 			'group_id'=>$gid,
			 			'access_id'=>$v->id,
			 		];
			 	}
			 } 
			 \DB::w()->delete('admin_group_access','group_id=?',$gid);
			 \DB::w()->insert_batch('admin_group_access',$batch);
			 \Session::flash('success',__('Save Success')); 
			 $this->redirect(url('admin/group/access',['id'=>$gid]));
			 
  		}
  		$this->view('access',[
  			'post'=>$post,
  			'data'=>$access['data'],
  			'actions'=>$access['actions'],
  			'ac'=>$ac,
  		]);
  	}
 	function indexAction(){ 
 	 	$posts = \DB::w()->from('admin_group')->all();
 		$this->view('group',['posts'=>$posts]);
 	}
   	function deleteAction(){
   		$id = $_GET['id'];
   		\DB::w()->delete('admin_group','id=?',[$id]);
   		\Session::flash('success',__('Delete Success')); 
		$this->redirect(url('admin/group/index'));
			 		
   	}
	function saveAction(){  
		$element = [
			'name'=>[
		 		'label'=>'group name',
		 		'element'=>'input',
		 	], 
		];
		$button = "save";
		if($_POST){
			\Validate::set('name',[
				['not_empty','message'=>__('not empty')], 
			]);
		 
			if(\Validate::run()){  
				if(!\DB::w()->from('admin_group')->where('name=?',[\Input::post('name')])->one()){
					\DB::w()->insert('admin_group',[
						'name'=>\Input::post('name'), 
						'create_at'=>date('Y-m-d H:i:s'),
					]);
					\Session::flash('success',__('Save Success')); 
			 		$this->redirect(url('admin/group/index'));
				} else{
					$error = __('Group name had exists');
				} 
			} 
			 
	 	} 
	  	$vali = \Validate::message(); 
	  	if(!$error)
 			$error = \Arr::to_str($vali); 
	 	$this->view('form',['error'=>$error,'element'=>$element,'button'=>$button,'title'=>__('Group')]);
	 	
	}
	 
}