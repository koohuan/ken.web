#### 下载composer

    curl -sS https://getcomposer.org/installer | php

#### 快速安装


    php composer.phar create-project --prefer-dist --stability=dev ken/web_skeleton  /path/to/application
    


独立安装
========
 
composer.json

	{
	 
		"require": {
			"php": ">=5.4"  
		} ,
		"config": {
			"preferred-install": "dist"
		}, 
		"autoload": { 
		    "files": ["libraries/alias.php","libraries/functions.php"],
		    "psr-4":{
			    "Ken\\Web\\":"libraries/",
		            "module\\": "module/",  
			    "widget\\": "widget/",
			    "tool\\": "tool/",
			    "third\\": "third/", 
			    "event\\": "event/" 	
		    }
		}
	}

执行 `php composer.phar dump-autoload`

public/index.php


	<?php 
	/**
	 	入口文件
		@auth Kang Sun <68103403@qq.com>
		@license BSD
		@date 2014 
	*/ 
	 
	session_start();
	header("Content-type: text/html; charset=utf-8");
	ini_set('error_reporting',1);
	error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));   
	require __DIR__.'/../vendor/autoload.php';   
	$local_config = __DIR__."/../config.local.php";
	$product_config = __DIR__."/../config.php";
	if(file_exists($local_config)){ 
		include $local_config; 
	}else
		include $product_config;  
	use Ken\Web\F;
	//日志
	Ken\Web\Log::start($log); 
	/**
		挂件
	*/
	F::set('widget',function(){ 
		 return new Ken\Web\Widget(__DIR__.'/assets' ,base_url().'assets');
	});

	F::set('lang',function(){ 
		 $lang =  new Ken\Web\Lang(__DIR__.'/../messages');
		 $lang->load();
		 return $lang;
	});
	/**
		缓存
	*/
	F::set('cache',function() use ($cache){ 
		return new Ken\Web\Cache($cache);  
	});
	 
	/**
		HTTP header 设置
	*/
	F::set('response',function(){ 
		return new Ken\Web\Response();
	});
	/**
		后台管理权限
	*/
	F::set('auth',function(){ 
		return new Ken\Web\Auth;
	});	
	F::set('user',function(){ 
		$user =  new Ken\Web\User($cache);  
		$user->username = null;
		return $user;
	});	
	/**
		视图
	*/
	F::set('view',function() use( $minify ){
		 $view_dir = base_path().'/view';
		 $theme_dir = public_path().'/themes';
		 $view = new Ken\Web\View($view_dir , $theme_dir);
		 $view->minify = $minify;
		 return $view;
	});
	/**
		主数据库
	*/
	F::set('db',function() use ($db){
		$config = $db[0];
		return new Ken\Web\DB($config[0],$config[1],$config[2]);  
	});
	/**
		从数据库
	*/
	F::set('db2',function() use ($db){
		if(count($db)>1){
			unset($db[0]);
			$i = array_rand ($db , 1);
			$config = $db[$i];
		}else{
			$config = $db[0];
		}
		return new Ken\Web\DB($config[0],$config[1],$config[2]); 
	}); 
	/**
		路由
	*/
	F::set('route',function(){ 
		return new Ken\Web\Route;
	});
	/**
		自动加载 route
	*/
	$route = F::get('route');  
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
		$route->run(); 
	}catch (Exception $e) { 
		F::get('view')->make('/error',['message'=>$e->getMessage(),'code'=>$e->getCode()]);  
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



config.php 如存在 config.local.php 将使用 `config.local.php`

	<?php 

	date_default_timezone_set('Asia/Shanghai');  
	/**
		memcache 设置
	*/
	$cache = [
		['host'=>'127.0.0.1','port'=>11211,'weight'=>60]
	];
	/**
		数据库设置，第一个为主库
	*/
	$db = [
		["mysql:dbname=ljftaichi;host=127.0.0.1","ljftaichi","ljftaichi258963."],
		 
	];
	//日志目录
	$log = __DIR__.'/temp/logs';

	//加密解密KEY
	$crypt_type = "AES";
	$crypt_key = 'ken.web';
	 
	//是否合并VIEW html代码
	$minify = false;

	 