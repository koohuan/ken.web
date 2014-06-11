<?php 
/** 
	依赖 Route.php
	必须定义 base_path  public_path 这两个函数
    View 视图
    layout举例  文件名为default.layout.php
    
    <?php echo $this->view['content'];?>
    
    View中使用
    
    <?php $this->layout('default');?>
	
	<?php echo $this->start('content');?>
	test
	<?php echo $this->end();?> 
 	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
 */
namespace Ken\Web;
class View
{  
	//默认theme
	static $theme = 'default';
	//BLOCK
 	public $block;
	public $block_id;   
	//theme dir 
	public $theme_dir;
	//view dir
	public $view_dir;
	public $view_file;
	public $theme_file; 
	//展示VIEW在LAYOUT中
	public $view;
	public $info;
	static $default; 
	static $obj;   
	static $minify = false;
	static function set_theme($name){ 
		static::$theme = $name;
	}
	function __construct(){    
		$this->view_dir = base_path().'/view'; 
		$this->theme_dir = public_path().'/themes/'.static::$theme;  
		static::$default = ['view'=>$this->view_dir,'theme'=>$this->theme_dir]; 
	}
	
	/**
		加载layout 同级文件
	*/
	function extend_layout($name,$par = []){
		$name = str_replace('.','/',$name);
		$dir = substr($this->block['layout'],0,strrpos($this->block['layout'],'/')+1);
		$file = $dir."$name.php";
		if(file_exists($file)){
			extract($par, EXTR_OVERWRITE); 
			include $file;
		}
	}
	/**
		加载view 同级内容
	*/
	function extend($name,$par = []){ 
		$name = str_replace('.','/',$name);
		$this->__ex($name); 
		$file = $this->find([$this->theme_file,$this->view_file]); 
		if(file_exists($file)){
			extract($par, EXTR_OVERWRITE); 
			include $file;
		}
	}
	
	//返回theme所在的url
	static function theme(){
		return Route::init()->base_url.'themes/'.static::$theme;
	}
 
	/**
	 	查找文件是否存在，存在后直接include 
	*/
	protected function find($arr = []){
		foreach($arr as $file){
			if(file_exists($file))  
				return $file;
		} 
		throw new \Exception("view not exists:\n".implode("\n",$arr)); 
	}
	protected function __ex($name){
		$this->view_file = $this->view_dir.'/'.$name.'.php'; 
		 
		//文件不存在，加载框架内核中的view 
		if(true === Route::$core && 
				Route::get_class() && 
				 !file_exists($this->view_file
			)){   
		 	$reflector = new \ReflectionClass(Route::get_class());
			$fn = $reflector->getFileName();
			$a = str_replace(base_path(),'',$fn);
			$a = str_replace('\\','/',$a);
			$a = substr($a,0,strrpos($a,'/')); 
		 	$this->view_file = base_path().$a."/view/".$name.'.php';
		} 
 		if($this->theme_dir){
			$this->theme_file = $this->theme_dir.'/'.$name.'.php';
		} 
	}
	static function make($name, $par = [])
	{ 
		$view = new Static; 
		return $view->render($name, $par);  
	}
	/**
		渲染视图
	*/
	function render($name, $par = [])
	{ 
	 	$name = str_replace('.','/',$name);
		if(substr($name,0,1)=='/'){
			$this->view_dir = static::$default['view'];
			$this->theme_dir = static::$default['theme'];
			$name = substr($name,1);
		} 
		$this->__ex($name); 
		$this->block['content'] = $this->find([$this->theme_file,$this->view_file]);    
		ob_start();
		extract($par, EXTR_OVERWRITE); 
		include $this->block['content']; 
		if(file_exists($this->block['layout']) ){
  			include $this->block['layout'];   
  		}
		$data = trim(ob_get_contents());   
		ob_end_clean();
		if(true === static::$minify)
			$data =  preg_replace(array('/ {2,}/','/<!--.*?-->|\t|(?:\r?\n[\t]*)+/s'),array(' ',''),$data);  
		echo $data;  
	}
	
	/**
	* 加载layout
	*/
	function layout($name , $par = [] ){  
		$d = $name = str_replace('.','/',$name); 
		if(strpos($name,'/')!==false)
			$name = substr($name,strrpos($name,'/')+1);
	 
		$view = $this->view_dir.'/'.$name.'.layout.php';
		$theme = $this->theme_dir.'/'.$name.'.layout.php';
		//上一层是否存在layout,目的是多个模块共用同一个theme下的laout
		$pre = substr($this->theme_dir,0,strrpos($this->theme_dir,'/')).'/'.$name.'.layout.php';
		//加载view下的layout
		$default_layout = base_path().'/view/'.static::$theme.'/'.$d.'.layout.php';   
		$this->block['layout'] = $this->find([$theme,$view,$pre,$default_layout]);
	}
	
	/**
	* 加载layout
	*/
	function start($name , $par = []){
		$this->block_id = $name;  
		ob_start();
		ob_implicit_flush(false);  
		extract($par, EXTR_OVERWRITE); 
	}
	/**
	* 加载layout
	*/
	function end(){   
		$this->view[$this->block_id] = ob_get_clean();
	}

	
 
}