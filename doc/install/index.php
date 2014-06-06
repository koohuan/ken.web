<?php 
/**
 	入口文件
	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
*/  
session_start();
header("Content-type: text/html; charset=utf-8"); 
require __DIR__.'/../vendor/autoload.php';     
error_reporting(Config::get('app.error_reporting')); 
ini_set('display_errors', 1);
//时区	
date_default_timezone_set(Config::get('app.timezone'));  
//启用日志
Log::init();   
//多语言
Lang::init()->load('app');
View::$minify = Config::get('app.minify');  
//VIEW
if($_GET['theme'])
	View::set_theme($_GET['theme']);
 
/**
	加载route目录下的路由文件
*/
$route_dir = realpath(__DIR__.'/../route');
$rlist = scandir($route_dir); 
foreach($rlist as $v){ 
	if($v=='.' || $v=='..' || $v=='.svn' || $v=='.git') continue; 
	include $route_dir."/$v";
}  
try { 
	Route::run(); 
}catch (Exception $e) { 
	dump($e->getMessage()); 
} 
/**
	项目物理路径
*/
function base_path(){ 
	return realpath(__DIR__.'/../'); 
}  
/**
	web可访问的 public 目录
*/
function public_path(){ 
	return __DIR__; 
}

