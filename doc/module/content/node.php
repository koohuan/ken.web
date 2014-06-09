<?php
/**
 	内容 CURD
	@auth Kang Sun <68103403@qq.com>
 	@date 2014
	
*/
namespace Ken\Web\doc\module\content;   
use Ken\Web\doc\module\content;
use Ken\Web\doc\module\content\field\base;
use Ken\Web\Vendor\Excel_XML as Export;
class node extends \AuthController{  
	public $table;
	public $post;
	public $co;
	public $gid;
	public $fid;
 	function init(){
 		parent::init();  
 		$this->access = 'content.node.index.'.$_GET['id'].'.';
 		\Menu::set('content.node.index.'.$_GET['id']);   
 		$this->gid = $id = $_GET['id'];
 		$this->fid = $_GET['fid'];
 		$this->post = $this->db->table('node_table')->pk($id);
 		$this->table = $this->post->slug; 
 		$cl = \DB::w()->sql("SHOW COLUMNS FROM ".$this->table)->all();
 		foreach($cl as $v){
 			$this->co[$v->field] = $v->field;
 		} 
 	}
 	//加载 classes\content\下的内容
 	function doAction(){
 		$action = $_GET['action'];
 		$id = $_GET['nid']; 
 		$arr = explode('.',$action); 
 		$cls = "\Ken\\Web\\doc\\module\\content\\".$arr[0]; 
 		$method = $arr[1]; 
 		$cls::$method($this->table,$id);
 	}
 	function deleteAction(){ 
 		$this->access .= "d"; 
 		\DB::w()->delete($this->table,'id=?',$this->fid);
 		\Session::flash('success',__('Delete Success')); 
 		$this->redirect(url('content/node/index',['id'=>$this->gid]));
 	}
 	//导出报表
 	function exportAction(){
 		$this->access .= "r";
 		$base = "\module\content\\field\\base";
 		$id = $_GET['id'];
 		$post = $this->db->table('node_table')->pk($id);
 		$title = $post->slug;
 	 	$fields = content::field($id);   
 	 	foreach($fields as $v){
			if(!$v->is_search) continue;
			$cls = "\module\content\\field\\".$v->field;
			$obj = new $cls($post->slug,$v); 
			$search .= "<label>".$v->label."</label>";  
			$search .= $obj->render();
		} 
 		$r = $base::$r; 
		$out = $report = [];
		foreach($fields as $v){
			if(!$v->is_report) continue; 
			$arr = explode('.',$v->slug);
			$report[] = $arr[0];
			$label[] = $v->label;
		} 
		$out[0] = $label;
		//按搜索的结果导出
		$get = $_GET; 
		unset($get['id'],$get['page']);
		$gid = $get['_id'];unset($get['_id']); 
		if($get){
			foreach($get as $k=>$v){
				$get[$r[$k]] = $v;
				unset($get[$k]);
			}
		}  
		$query = \DB::w()
	            ->table($post->slug);
	    //where 条件 搜索查寻
		if($gid)
			$query = $query->where("id=?",[$gid]); 
		if($get){
			foreach($get as $k=>$v){
				if($v){
					$query = $query->where("`".$k."` like ?",["%".$v."%"]); 
				}
			}
		} 
	    $posts = $query->order_by('id desc')->all(); 
		$i = 1;   
		foreach($posts as $post){ 
			foreach($report as $k=>$r){ 
				$out[$i][] = $post->$r; 
			}
			$i++;
		} 
		 
		$xls = new Export;
		$xls->addArray ( $out );
		$xls->generateXML ( $title );
 
 	}
 	//列表中的搜索 
 	protected function db($table,$r = null){  
 			$get = $_GET;
 			unset($get['id'],$get['page']);
 			$gid = $get['_id'];unset($get['_id']); 
 			if($get){
 				foreach($get as $k=>$v){
 					$_k = $r[$k];
 					if(strpos($_k,':')!==false) {
 						$ar = explode(':',$_k);
 						$_k = $ar[0];
 					}
 					if($v)
 						$get[$_k] = $v;
 					unset($get[$k]);
 				}
 			} 
 			$url = url('content/node/index');
 			$query = \DB::w()->table($table);
 			//where 条件 搜索查寻
 			if($gid)
 				$query = $query->where("id=?",[$gid]);  
 			if($get){
 				foreach($get as $k=>$v){
 					if($v){
 						$query = $query->where("`".$k."` like ?","%".$v."%"); 
 					}
 				}
 			} 
 			//如果有排序字段
 			if($this->co['top'])
 				$sort[] = "top desc";
 			if($this->co['sort'])
 				$sort[] = "sort desc";
 			$sort[] = "id desc"; 
		    $query = $query->order_by(implode(',',$sort)); 
			$vo = $query->page($url);  
			return ['posts'=>$vo->posts,'paginate'=>$vo->pages];
 	}
 	//列表
 	function indexAction(){
 		$this->access .= "r";
 		$base = "\Ken\\Web\\doc\\module\\content\\field\\base";
 		$id = $_GET['id'];
 		$post = $this->db->table('node_table')->pk($id);
 		$m = $post->slug;
 	 	$fields = content::field($id); 
 	 	foreach($fields as $v){
			if(!$v->is_index) continue; 
			$fs[$v->label] = content::slug_field($v->slug);
		}    
		foreach($fields as $v){
			if($v->is_form) {
				$is_form = TRUE;  
			}
			if($v->is_search) {
				$is_search = TRUE;  
			}
			if($v->is_report){
				$report = TRUE;  
			}
		}  
		$search = '';
		foreach($fields as $v){
			if(!$v->is_search) continue;
			$cls = "\Ken\\Web\\doc\\module\\content\\field\\".$v->field;
			$obj = new $cls($post->slug,$v); 
			$search .= "<label>".$v->label."</label>";  
			$search .= $obj->render();
		} 
	 
		$r = $this->db($m,$base::$r); 
		 
 		$this->view('node_index',[
 			'fs'=>$fs,
 			'post'=>$post,
 			'search'=>$search,
 			'paginate'=>$r['paginate'] , 
 			'posts'=>$r['posts'],
 			'report'=>$report,
 			'is_search'=>$is_search,
 			'is_form'=>$is_form,
 			'id'=>$id,
 		]);
 	} 
 	//创建 更新
 	function saveAction(){ 
 		$id = $_GET['id'];
 	 	$fields = content::field($id);
 	 	$base = "\Ken\\Web\\doc\\module\\content\\field\\base";
 	 	$form = "";
 	 	//如果是编辑
 	 	$nid = $_GET['nid'];
		if($nid){
			$this->access .= "u";
			$one = \DB::w()->table($this->table)->pk($nid);
		} else{
			$this->access .= "c";
		}
		//
		foreach($fields as $v){
			if(!$v->is_form) continue;
			$cls = "\Ken\\Web\\doc\\module\\content\\field\\".$v->field;
			$obj = new $cls($this->post->slug,$v); 
			$form .= "<label>".$v->label."</label>"; 
			//
			$arr = explode('.',$v->slug);
			$n = $arr[0]; 
			$relation = base::relation($v);
			/**
			* 加载 widget
			*/
			$data = $base::$save;
			$inner = $data[$this->table][$v->slug];
			$widget = $inner['_widget']; 
		 	if($widget){ 
				foreach($widget as $_k=>$_v){  
					if(!is_array($_v)) $_v = [];
					if(!array_key_exists('id',$_v) ) $_v['id'] = "#".$inner['name'];
					$form .= widget($_k,$_v); 
				}
			}
			 
			if($relation){
				$relation['nid'] = $nid;
				$form .= $obj->render($relation);
			}else{
				$form .= $obj->render($one->$n);	 
			}
		}     
 	 	if($_POST){ 
 	 		//取得要保存的数据库结构 
 	 		$e = content::save($data,$nid ,$this->co); 
 	 		exit($e);
 	 	}
 	 	$this->view('node',['form'=>$form,'post'=>$this->post,'one'=>$one,'widgets'=>$widgets]);
 	}
 	
 	
 	//向上一格
 	function upAction(){
 		$id = $_GET['id'];
 		$fid = $_GET['fid'];
 		$one = $this->db->table($this->table)->where('id=?',$fid)->one(); 
 		$up = $this->db->table($this->table)->where('sort>?',$one->sort)->order_by('sort asc')->one();  
  		if(!$up->sort) goto UPE;
 		if($up->sort == $one->sort) $one->sort = $one->id;
 		$this->db->update($this->table,['sort'=>$up->sort] ,'id=?',[$fid]);
 		$this->db->update($this->table,['sort'=>$one->sort] ,'id=?',[$up->id]);
 		\Session::flash('success',__('Success'));
 		UPE:
 		$this->redirect(url('content/node/index',['id'=>$id]));
 	}
  
  	//向下一格
 	function downAction(){
 		$id = $_GET['id'];
 		$fid = $_GET['fid'];
 		$one = $this->db->table($this->table)->where('id=?',[$fid])->one(); 
 		$up = $this->db->table($this->table)->where('sort<?',$one->sort)->order_by('sort desc')->one(); 
 		if(!$up->sort) goto DOWNE; 
 		$this->db->update($this->table,['sort'=>$up->sort] ,'id=?',[$fid]);
 		$this->db->update($this->table,['sort'=>$one->sort] ,'id=?',[$up->id]);
 		\Session::flash('success',__('Success'));
 		DOWNE:
 		$this->redirect(url('content/node/index',['id'=>$id]));
 	}
 	
 	//置顶
 	function uppAction(){
 		$id = $_GET['id'];
 		$fid = $_GET['fid'];
  		$this->db->update($this->table,['top'=>time()] ,'id=?',[$fid]);
 		\Session::flash('success',__('Success'));
 		$this->redirect(url('content/node/index',['id'=>$id]));
 	}
 	//取消置顶
 	function closeAction(){
 		$id = $_GET['id'];
 		$fid = $_GET['fid'];
  		$this->db->update($this->table,['top'=>0] ,'id=?',[$fid]);
 		\Session::flash('success',__('Success'));
 		$this->redirect(url('content/node/index',['id'=>$id]));
 	}
 	
	 
}