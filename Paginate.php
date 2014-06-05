<?php
/**
  	分页
 	类似淘宝分页
 
	$query = \F::get('db')->table($this->table);
	$row = $query->count()->one();
	
	 
	$paginate = new \Paginate($row->num,1); 
	$paginate->url = $this->url;
	$limit = $paginate->limit;
	$offset = $paginate->offset;
	
	$paginate = $paginate->show();
	
    
    $query = \F::get('db')
    			->table($this->table)
    			->limit($limit)
    			->offset($offset);
	$posts = $query->all();
 	
	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014
*/
namespace Ken\Web;

class Paginate{
	public $page;
 	public $pages;
 	public $url;
 	public $size;
 	public $count;
 	public $limit;
 	public $offset;
 	//不保留参数
 	public $get = [];
	function __construct($count,$size=10){ 
		$this->count = $count;
		$this->size = $size;
     	//总页数
     	$this->pages = ceil($this->count/$this->size); 
     	//当前页面
     	$this->page = (int)$_GET['page'];  
     	if($this->pages<1) return;
     	if($this->page <= 1) 
     		$this->page = 1;  
     	if($this->page >= $this->pages)
     		$this->page = $this->pages;   
      	
      	$this->offset = $this->size*($this->page-1);
     	$this->limit = $this->size;  
	}
	/**
		生成URL函数，如有需要，可自行改写
	*/
	function url($url,$par = []){
		return url($url,$par);
	}
	/**
	* 显示分页
	*/
	function show($class='pagination'){   
		$str = '<ul class="'.$class.'">';
		$pre = $this->page-1; 
		$p = $_GET;
		$p['page'] = $pre>0?$pre:1; 
		if($pre>0)
			$str .= '<li><a href="'.$this->url($this->url,$p).'">&laquo;</a></li>'; 
		if($this->pages<2) return; 
		$pages[1] = 1;
		$pages[2] = 2;
		$i = $this->page-2<=1?1:$this->page-2;
		$e = $this->page+2>=$this->pages?$this->pages:$this->page+2; 
		if($e<5 && $this->pages>=5)
			$e = 5;    
	  	$pages['s'] = null; 
 	  	if($i>0){
			for($i;$i<$e+1;$i++){   
				$pages[$i] = $i; 
			}  
		}
		$j=0;
		foreach($pages as $k=>$v){
			if($j==3) $n = $k;
			$j++;
		} 
		
		if($this->pages>5){
			if($n!=3) 
		 		$pages['s'] = "..."; 
			if($e<$this->pages)
				$pages['e'] = "...";
		}
	 	$p = $_GET;   
	 	if($this->get){
	 		foreach($this->get as $d){
	 			unset($p[$d]);
	 		}
	 	}
	 		
		foreach($pages as $j){
			$active = null;
			if($j==$this->page)
				$active = "class='active'";
			if(!$j) continue;
			$p['page'] = $j;  
			if($j=='...')
				$str .="<li $active><a href='#'>$j</a></li>"; 
			else
				$str .="<li $active><a href='".$this->url($this->url,$p)."'>$j</a></li>"; 
		}
		$p['page'] =$next = $this->page+1; 
		if($next<=$this->pages)
			$str .= '<li><a href="'.$this->url($this->url,$p).'">&raquo;</a></li>';
		$str .= "</ul>";
 		return $str;
	}
}