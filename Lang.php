<?php  
/** 
	 
	Lang::init($path)->load('app');
	
	function __($key,$alias='app'){ 
		return Lang::get($key,$alias); 
	} 
   
	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
*/
namespace Ken\Web;  
class Lang
{ 
 	public $dir;
 	static $lang = 'zh_CN';
 	static $obj;
 	static $init;
	function __construct($path = null){
		if(!$path)
	 		$this->dir = public_path().'/../messages';			
	 	else
	 		$this->dir = $path;
	}
 
	/**
		加载文件
	*/
	function load($alias='app'){
		if(!static::$obj[static::$lang][$alias]){
			$file = $this->dir.'/'.static::$lang.'/'.$alias.'.php';
			if(file_exists($file)){
				static::$obj[static::$lang][$alias] = include $file;
			}
		}
	}
	function init(){
		if(!isset(static::$init)){
			static::$init = new Static;
		}
		return static::$init;
	}
	/**
	 	取得翻译
	*/
	static function get($key , $alias = 'app'){
		return static::$obj[static::$lang][$alias][$key]?:$key;
	}
	
	
	 
     
   
   
}