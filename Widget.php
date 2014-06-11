<?php  
/** 
	依赖 File 类
	必须定义 base_path  public_path 这两个函数 
	composer.json 如下 对应 widget 目录 
 
 	Widget实现    
	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
*/
namespace Ken\Web;  
class Widget
{ 
	static $core = true;
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
    	if(strpos($n,'Ken/Web/Widget')!==false && true === static::$core ){
    		$new = $n.substr($n,strrpos($n,'/'));
    		$new = str_replace('/','\\',$new);
    		$reflector = new \ReflectionClass($new);
			$fn  =  $reflector->getFileName(); 
			$a = str_replace('\\','/',$fn);
			$a = substr($a,0,strrpos($a,'/')); 
		 	$file = $a."/view/".$view.'.php';  
    	}else{
    		$n = base_path().'/'.$n;
    		$file = $n."/view/$view.php";  
    	} 
    	if(file_exists($file)){
			extract($par, EXTR_OVERWRITE); 
			include $file;
		} 
    }
    /**
    	显示widget
    */
    static function init($class,$par = []){ 
    	if(!class_exists($class)) $class = "\Ken\\Web\\".ucfirst($class);
    	$obj  = new $class();
    	if($par){
    		foreach($par as $k=>$v)
    			$obj->$k = $v;
    	}
    	return $obj->run(); 
    }
     
   
   
}