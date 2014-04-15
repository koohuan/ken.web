<?php  
/** 
	依赖 File 类
	必须定义 base_path  public_path 这两个函数
	
	composer.json 如下 对应 widget 目录
	
	"autoload": {  
	    "psr-4":{ 
	        "app\\": "app/",
		    "lib\\": "lib/",
		    "widget\\": "widget/",
		    "event\\": "event/" 	
	    }
	}
 	Widget实现    
	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
*/
namespace Ken\Web;  
class Widget
{ 
	//要发布到的目录
	static $to;
	//相对URL
	public $base;
	static $_url;
	public $r;
	function __construct(){ 
		if(!isset(static::$to))
			static::$to = public_path().'/assets'; 
		if(!isset(static::$_url))
			static::$_url = base_url().'assets'; 
		
	}
	/**
		发布资源到指定目录
	*/
    function publish($dir){
    	$r = new \ReflectionClass($this);	
    	//当前使用的widget name
    	$widget_name = $r->name; 
    	$widget_name =  substr($widget_name,strrpos($widget_name, '\\')+1 ) ; 
     	File::cpdir($dir,static::$to ,$widget_name ); 
     	return static::$_url.'/'.$widget_name.'/'; 
    }
    /**
    	渲染视图
    */
    function view($view,$par = []){
    	$r = new \ReflectionClass($this);
    	$n = str_replace('\\','/',$r->name);
    	$n = substr($n,0,strrpos($n,'/'));
		$file = base_path().'/'.$n."/view/$view.php"; 
    	if(file_exists($file)){
			extract($par, EXTR_OVERWRITE); 
			include $file;
		}
    	 
    }
    /**
    	显示widget
    */
    static function init($class,$par = []){ 
    	$obj  = new $class();
    	if($par){
    		foreach($par as $k=>$v)
    			$obj->$k = $v;
    	}
    	return $obj->run(); 
    }
     
   
   
}