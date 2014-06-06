<?php
/**
 	content type
	@auth Kang Sun <68103403@qq.com>
 	@date 2014
	
*/
namespace Ken\Web\doc\module\content;  
use Ken\Web\Vendor\Spyc;
class type extends \AuthController{ 
  	 
 	function init(){
 		parent::init();
 		\Ken\Web\Menu::set('content.type'); 
 	 	
 	 	
 	}
 	function pageAction(){
 		$this->view($_GET['name']);
 	}
 	//保存内容类型
 	function saveAction(){
 		
 		
 		$id = $_GET['id'];
 		if($id>0){ 
 			$one = $this->db->table('node_table')->pk($id);
 		}
 		if($_POST){
 			$a = \DB::w()->sql('show tables')->all();
 			foreach($a as $v){
 				$n[] = trim(\Arr::first($v));
 			}
 			if(!in_array($_POST['slug'],$n)){
 				\Session::flash('error',__('Table not exists'));
 				$this->redirect(url('content/type/save'));
 			}
 		 	
 			
 			if($id>0){
 				$this->db->update('node_table',$_POST,'id=?',[$id]);
 			}else{
 				$this->db->insert('node_table',$_POST);
 			}
 			\Session::flash('success',__('Success'));
 			$this->redirect(url('content/type/index'));
 		}
 		$this->view('save',['one'=>$one]);
 	}
 	function indexAction(){
 		$this->title = __('Content Type');
 	 	$all = $this->db->table('node_table')->all();
 		$this->view('type',['all'=>$all]);
 	}
 	//字段设置
 	function setAction(){ 
 		$this->title = __('Content Setting');
 		$id = $_GET['id'];
 		$fid = $_GET['fid'];
 	 	$all = $this->db->table('node_field')->where('table_id=?',[$id])->order_by('top desc,sort desc')->all();
 	 	$post = $this->db->table('node_table')->pk($id);
 	 	if($fid){
 	 		$one = $this->db->table('node_field')->where('id=?',[$fid])->one();
 	 		$one->widget = Spyc::YAMLDump(unserialize($one->widget));
 	 		$one->validate = Spyc::YAMLDump(unserialize($one->validate));
 	 		$one->values = Spyc::YAMLDump(unserialize($one->values));
 	 	}
 	 	$g = scandir(__DIR__.'/field/');
		unset($vali);
 		$field[] = __('Please Select');
 		foreach($g as $v){ 
 			if($v != '.' && $v != '..' && $v != 'base.php'){
 				$f = substr($v,0,strrpos($v,'.'));
 				$field[$f] = $f;
 			}
 		}
 		if($_POST){ 
 			$_POST['table_id']=$id;
 			if($_POST['widget']){
 				$_POST['widget'] = serialize(Spyc::YAMLLoadString($_POST['widget'])); 
 			}
 			if($_POST['validate']){
 				$_POST['validate'] = serialize(Spyc::YAMLLoadString($_POST['validate'])); 
 			} 
 			if($_POST['values']){
 				$_POST['values'] = serialize(Spyc::YAMLLoadString($_POST['values'])); 
 			} 
 			$_POST['is_m_value'] = $_POST['is_m_value']?1:0;
 			$_POST['is_report'] = $_POST['is_report']?1:0;
 			$_POST['is_form'] = $_POST['is_form']?1:0;
 			$_POST['is_index'] = $_POST['is_index']?1:0;
 			$_POST['is_search'] = $_POST['is_search']?1:0;
 			//$_POST['values'] = $_POST['values']?:null;
 			if($fid){
 				$this->db->update('node_field',$_POST,"id=?",[$fid]);
 			}else{
 				$k = $this->db->insert('node_field',$_POST);
 				$this->db->update('node_field',['sort'=>$k],"id=?",[$k]);
 			}
 			\Session::flash('success',__('Success'));
 			$this->redirect(url('content/type/set',['id'=>$id,'fid'=>$fid]));
 		}
 	 
 		$this->view('set',[
 			'all'=>$all,
 			'field'=>$field,
 			'id'=>$id,
 			'one'=>$one,
 			'post'=>$post
 		]);
 	}
 	//删除字段
 	function deleteAction(){
 		$id = $_GET['id'];
 		$fid = $_GET['fid'];
 		$this->db->delete('node_field',"id=?",[$fid]);
 		\Session::flash('success',__('Success'));
 		$this->redirect(url('content/type/set',['id'=>$id]));
 	}
 	
 	//向上一格
 	function upAction(){
 		$id = $_GET['id'];
 		$fid = $_GET['fid'];
 		$one = $this->db->table('node_field')->where('id=?',[$fid])->one(); 
 		$up = $this->db->table('node_field')->where('sort>? and table_id=? and top=?',[$one->sort,$one->table_id ,0])->order_by('sort asc')->one();  
  		if(!$up->sort) goto UPE;
 		if($up->sort == $one->sort) $one->sort = $one->id;
 		$this->db->update('node_field',['sort'=>$up->sort] ,'id=?',[$fid]);
 		$this->db->update('node_field',['sort'=>$one->sort] ,'id=?',[$up->id]);
 		\Session::flash('success',__('Success'));
 		UPE:
 		$this->redirect(url('content/type/set',['id'=>$id]));
 	}
  
  	//向下一格
 	function downAction(){
 		$id = $_GET['id'];
 		$fid = $_GET['fid'];
 		$one = $this->db->table('node_field')->where('id=?',[$fid])->one(); 
 		$up = $this->db->table('node_field')->where('sort<? and table_id=? and top=?',[$one->sort,$one->table_id,0])->order_by('sort desc')->one(); 
 		if(!$up->sort) goto DOWNE; 
 		$this->db->update('node_field',['sort'=>$up->sort] ,'id=?',[$fid]);
 		$this->db->update('node_field',['sort'=>$one->sort] ,'id=?',[$up->id]);
 		\Session::flash('success',__('Success'));
 		DOWNE:
 		$this->redirect(url('content/type/set',['id'=>$id]));
 	}
 	
 	//置顶
 	function uppAction(){
 		$id = $_GET['id'];
 		$fid = $_GET['fid'];
  		$this->db->update('node_field',['top'=>time()] ,'id=?',[$fid]);
 		\Session::flash('success',__('Success'));
 		$this->redirect(url('content/type/set',['id'=>$id]));
 	}
 	//取消置顶
 	function closeAction(){
 		$id = $_GET['id'];
 		$fid = $_GET['fid'];
  		$this->db->update('node_field',['top'=>0] ,'id=?',[$fid]);
 		\Session::flash('success',__('Success'));
 		$this->redirect(url('content/type/set',['id'=>$id]));
 	}
 	
	 
}