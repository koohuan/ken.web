<?php namespace Ken\Web\doc\module; 
/**
    Query Builder  使用
    
     
	query_all('news',[2]);
	//分页 
	query_page('news',$params=[],[$page_url,$per_page]);
	
	//分页
	\classes\query::page(1,[url('site/index'),2]);
	@auth Kang Sun <68103403@qq.com>
 	@date 2014
	
*/ 
class query{ 
	static $cache = false;
	/**
	* 一条数据
	*/
	static function one(){
		$arg = func_get_args(); 
		$arg[] = "one"; 
		return call_user_func_array(['\classes\query','get'],$arg); 
	}
	/**
	* 所有数据
	*/
	static function all(){
		$arg = func_get_args();  
		$arg[] = "all"; 
		return call_user_func_array(['\classes\query','get'],$arg); 
	} 
	/**
	* 分页
	* page(   ... [$url ,$per_page = 10 ,$count = "*"] )
	*/
	static function page(){
		$arg = func_get_args();  
		$arg[] = "page";  
		return call_user_func_array(['\classes\query','get'],$arg); 
	} 
	/**
	\classes\query::get(1,[1,2],[2],'one');
	\classes\query::get(1,[1,2],[2],'all');
	该方法禁止直接调用 
	*/
	static function get(){  
		$arg = func_get_args(); 
		$id = $arg[0];
		$fun = end($arg)?:"all";  
		$page = [];
		//分页
		if($fun=='page'){
			$page = prev($arg); 
		}
		$db = \DB::w()->table('query_build');
		if(is_numeric($id)){
			$one = $db->pk($id);
		}else{
			$one = $db->where('slug=?',[$id])->one();
		} 
		$sql = $one->sql;
	 	$ar = explode("\n",$sql);  
	 	\DB::w()->select("id")->table('query_build');
	 	$query = \DB::w();  
	 	$i = 0;
	 	foreach($ar as $v){
	 		$v = trim($v);
	 		$n = strpos($v," ");
	 		$a = strtoupper(trim(substr($v,0,$n)));
	 		$b = trim(substr($v,$n+1));  
 			if(strpos($a,'WHERE')!==FALSE){
 				$i++;
 				$where = $arg[$i];
 				$query = $query->$a($b,$where);
 			}else
 				$query = $query->$a($b); 
	 	}    
	 	if(true === static::$cache) {
	 		$query = $query->cache();  
	 	}
	 	$data = call_user_func_array([$query,$fun], $page );
	  	return $data; 
	}
	
 
	
}