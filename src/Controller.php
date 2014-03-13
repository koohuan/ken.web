<?php 
/**
	依赖 Route.php View.php
	依赖 base_path函数，返回项目根目录，非WEB根目录
	依赖项目结构 
	
    app  --- 功能模块
        admin
             login.php
    lib   --- 存在公共类
    route 
        default.php --- 路由文件
    temp  --- 日志目录
    view  --- 视图目录
    webroot  --- 网站根目录
        index.php  --- WEB可访问根目录
        themes
            default  ---默认theme
    widget   --- 挂件目录
    composer.json
    config.php  --- 系统配置


	F::set('route',function(){ 
		return new Route;
	});
	
	通过F加载View
    
    F::set('view',function(){
		 $view_dir = base_path().'/view';
		 $theme_dir = public_path().'/themes/default';
		 return new View($view_dir , $theme_dir);
	});


    基础控制器
    
    该控制器依然为非必须的
 
	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
 */
namespace Ken\Web;
abstract class Controller
{ 
	public $id;
	public $action;
	public $theme = 'default';
	public $active;
	public $title;
	function __construct(){ 
 		 $ar = F::get('route')->class;
 		 $this->id= str_replace('\\','/',$ar[0]);
 		 $this->action= $ar[1];
 		 /**
 		 	设置当前控制器的VIEW目录
 		 	如 app/admin 的 view目录直接在 app/admin/view 下
 		 	theme对应的目录在 public/themes/default/admin/
 		 */
 		 $id = $this->id;
 		  
 		 F::get('view')->view_dir = base_path().'/'.
 		 				substr($id,0,strrpos($id,'/')).'/view';
 		  
 		 $dir = F::get('view')->theme_dir; 
 		 $t = substr($id,strpos($id,'/')+1);
 		 $t = substr($t,0,strrpos($t,'/'));
 		 F::get('view')->theme_dir = substr($dir,0,strrpos($dir,'/')+1)
 		 							.$this->theme."/".
 		 							$t;  
 		 //设置theme url,这样在View中可用$this->theme()取得当前URL
 	 	 F::get('view')->theme = $this->theme; 
 	 	 $this->init(); 
 	}
 	/**
 		渲染视图
 	*/
 	protected function  view($view,$data = []){ 
 		F::get('view')->active = $this->active;
 		F::get('view')->title = $this->title;
 		return F::get('view')->make($view,$data);
 	}
	
	function redirect($url){
		redirect($url);
	}
	function refresh(){
		refresh(); 
	}
	function init(){}
 
}