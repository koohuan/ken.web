路由
========
src/Route.php

在项目中可以用 `url($url,$par=[])` 函数生成URL



该类可独立使用

	use PHP\Classes\Route;
	$route = new Route;
	//首页
	$route->get('/',function(){
		echo 1;
	});
	//登录
	$route->all('login/<name:\w+>','app\login\$name@index','login');  
	
	// aa 为url地址，home为生成url链接所用的名称，如果没有 home, url('admin/index/index');
	// 即对 app\这个namespace 支持快速生成URL。 	
	$route->get('aa',"app\admin\index@index",'home'); 
	
	//生成url方式 url('admin/index/test',['id'=>1,'g'=>2]);
	$route->get('post/<id:\d+>/<g:\d+>',"app\admin\index@test");
	/**
	* 存在优先级，
	* 相同路由有参数的放到下面
	* 如下所示
	*/
	$route->get('payadmin','app\pay\admin@list');
	$route->get('payadmin/<page:\d+>','app\pay\admin@list');

	生成URL
	echo $route->url('post',['id'=>1,'d'=>3]);
	
	//执行路由
	
	try { 
		$route->run(); 
	}catch (Exception $e) { 
		if($e->getCode() == 404) {
	     	throw new \Exception('404 page not find');
	    } else{
	    	dump($e->getMessage());
	    }
	} 
 	
 	
 	
apache 	.htaccess 配置 
 	
 	RewriteEngine On 
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteRule ^ index.php [L]