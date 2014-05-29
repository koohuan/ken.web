环境要求
========
#PHP	5.4+
#Apache rewrite

常用功能说明
============

菜单选中

	Menu::set($name);
	if(Menu::active($name)){
	
	}

数组转成字符串
	
	\Arr::to_str($arr,"\n");  `"\n"` 第二个参数为分隔字符串

对图片进行缩放等处理
========
方法一 
	
	`$url` 来自数据库 files表中的url字段

 	$op = [
		'bgcolor' => '#f00', 
	        'quality' => 50,
	        'actions' =>[
	            'resize'=>[200, 180], 
	        ]
	]; 
	$u = \Image::set($url,$op);    


方法二
	
 	$a = public_path().'/upload/1.png';
	$b = public_path().'/upload/2.png';
	$op = ['quality'=>75];
	Image::init($op)->load($a)->output($b); 
	Image::init($op)->load($a)->save($b);



    
    
 


 