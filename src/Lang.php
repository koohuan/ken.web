<?php  
/** 
	 
	F::set('lang',function(){ 
		 $lang =  new Lang(__DIR__.'/../messages');
		 $lang->load();
		 return $lang;
	});
	
	function __($key,$alias='app'){ 
		return F::get('lang')->get($key,$alias); 
	} 
   
	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
*/
namespace Ken\Web;  
class Lang
{ 
 	public $dir;
 	public $lang = 'zh_CN';
 	static $obj;
	function __construct($dir){
	 	$this->dir = $dir;			
	}
	/**
		加载文件
	*/
	function load($alias='app'){
		if(!static::$obj[$this->lang][$alias]){
			$file = $this->dir.'/'.$this->lang.'/'.$alias.'.php';
			if(file_exists($file)){
				static::$obj[$this->lang][$alias] = include $file;
			}
		}
	}
	/**
	 	取得翻译
	*/
	function get($key , $alias = 'app'){
		return static::$obj[$this->lang][$alias][$key]?:$key;
	}
	
	
	 
     
   
   
}