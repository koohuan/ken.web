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

取得字符串中的图片

	Img::get_local_one($str);//本地图片
	Img::get_one($str);	     //所有图片

移除内容中的图片元素

	Img::remove($content);

图片的宽高

	Img::wh($url);

图片是否是GIF
	
	Img::is_animated_gif($file);


判断一个图片是否是包含alpha通道的png

	Img::is_alpha_png($file);


对图片进行缩放等处理
========  
	
	`$url` 来自数据库 files表中的url字段

 	$op = [
		'bgcolor' => '#f00', 
	        'quality' => 50,
	        'actions' =>[
	            'resize'=>[200, 180], 
	        ]
	]; 
	$u = \Image::set($url,$op);   


对已经进行缩放等处理的URL还原
	
	Image::get($img);

当图片不存在时请加`.htaccess`

	RewriteCond %{REQUEST_FILENAME} !\.(jpg|jpeg|png|gif|bmp)$ 	
	RewriteRule /upload/image/(.*)$ /admin/image?id=upload/image/$1 [NC,R,L]  


    
    
查看开发手册

	Route::get('doc',function(){ 
		//路径为框架所在doc目录下的doc.php文件路径
		echo include("../vendor/ken/web/doc/doc.php");
	}); 


 