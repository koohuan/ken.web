<?php
/**
 	query builder
 	
	@auth Kang Sun <68103403@qq.com>
 	@date 2014
	
*/
namespace Ken\Web\doc\module\query;  
class site extends \AuthController{ 
  	public $table = 'query_build';
  	function init(){
  		parent::init();
  		\Ken\Web\Menu::set('query.site');  
  	}
  	function indexAction(){
  		$query = \DB::w()->table($this->table);
		$row = $query->count()->one(); 
		$paginate = new \Paginate($row->num,50); 
		$paginate->url = url('query/site/index');
		$limit = $paginate->limit;
		$offset = $paginate->offset; 
		//显示分页条，直接输出 $paginate
		$paginate = $paginate->show(); 
		$query = \DB::w()
		            ->table($this->table)
		            ->limit($limit)
		            ->offset($offset);
		$posts = $query->all();
  		$this->view('index',[
  			'posts'=>$posts,
  			'paginate'=>$paginate,
  		]);
  	}
 	function editAction(){
 		$id = $_GET['id'];
 		if($id){
 			$post = \DB::w()->table($this->table)->pk($id);
 		}
 		if($_POST){
 			$memo = \Input::post('memo');
 			$slug = \Input::post('slug'); 
 			$sql = \Input::post('sql');
 			if(!$id && !\DB::w()->table($this->table)->where('slug=?',[$slug])->one()){
 				\DB::w()->insert($this->table,[
 					'memo'=>$memo,
 					'slug'=>$slug,
 					'sql'=>$sql,
 				]);
 			}else{
 				\DB::w()->update($this->table,[
 					'memo'=>$memo,
 					'slug'=>$slug,
 					'sql'=>$sql,
 				],'id=?',[$id]);
 			}
 			
 			$this->refresh(); 		
 		}
 		$this->view('edit',[
 			'post'=>$post
 		]);
 	}
 	 
	 
}