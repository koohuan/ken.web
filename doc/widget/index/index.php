<?php
/**
	自动化生成表单
	widget('index',[
		 'table'=>$table,
		 'fields'=>[
		 	'username'=>[
		 		'label'=>'username',
		 		'element'=>'input',
		 	], 
		 ], 
	]); 
 
	$btn = [
			'display'=>['admin/server/display',['id'=>'{id}'] ,[
				1=>'<span class="glyphicon glyphicon-ok"></span>',
				0=>'<span class="glyphicon glyphicon-remove"></span>', 
			]],
			
		];
		
*/
namespace Ken\Web\doc\widget\index; 

class index extends \Widget{ 
  
	public $fields = []; // 参数 option 是 select html option[]
 	public $table;
 	public $url;
 	public $search;
 	public $par = [];
 	/**
 		$btn = [
			'update'=>['news/admin/save',['id'=>'{id}']], 
			'delete'=>['news/admin/delete',['id'=>'{id}'],'option'=>[
					'click'=>"return confirm('".__('do you confirm delete?')."')"
				]], 
		];
 	*/
 	public $btn = [];
	function run(){  
		if(!$this->fields) return;
		foreach($this->fields as $k=>$v){
			$label[$k] = $v['label']?:$k;
		}  
		if($_GET){
			foreach($_GET as $k=>$v){   
				 $or_where[] = [$k." LIKE ? ",["".$v."%"]]; 
			}
		} 
		$query = \F::get('db')->table($this->table);
		//条件搜索
		if($or_where){
			foreach($or_where as $v){ 
				$query = $query->where($v[0],$v[1]);
			}
		}
	    
	    if($this->par){
			foreach($this->par as $k=>$v){
				$query = $query->$k($k , $v);
			}
		}
		$row = $query->count()->one(); 
		
		$paginate = new \Paginate($row->num,10); 
		$paginate->url = $this->url;
		$limit = $paginate->limit;
		$offset = $paginate->offset;
		
		$paginate = $paginate->show();
		
	    
	    $query = \F::get('db')
	    			->table($this->table);
	    
	    //条件搜索
		if($or_where){
			foreach($or_where as $v){ 
				$query = $query->where($v[0],$v[1]);
			}
		}
		
	    if($limit)
	    		$query = $query->limit($limit);
	    if($offset)
	    		$query = $query->offset($offset);
	    
		$posts = $query->all();
	 
	    
		$this->view('index',[
			'table'=>$this->table,
			'fields'=>$this->fields,
		 	'label'=>$label,
		 	'posts'=>$posts,
		 	'search'=>$this->search,
		 	'paginate'=>$paginate,
		 	'btn'=>$this->btn
		]);
 		
	}
}