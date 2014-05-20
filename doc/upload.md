上传文件  
========
composer 依赖 `"codeguy/upload": "*"`	
 

	$upload = new Upload();
	$upload->image('foo');//foo为表单元素名

取得数据库返回信息。

	$upload->get(); 

返回信息 

	成功写入 返回true,
	已存在  返回 false
	错误    返回  错误信息
	
    
数据库结构
		
  	CREATE TABLE `files` (
	  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  `name` varchar(200) NOT NULL,
	  `url` varchar(255) NOT NULL,
	  `ext` varchar(50) NOT NULL,
	  `mime` varchar(100) NOT NULL,
	  `size` int NOT NULL,
	  `md5` varchar(32) NOT NULL DEFAULT '',
	  `memo` varchar(200) NOT NULL
	) COMMENT='' ENGINE='MyISAM' COLLATE 'utf8_general_ci'; 

表单

	<form method="POST" enctype="multipart/form-data">
	    <input type="file" name="foo" value=""/>
	    <input type="submit" value="Upload File"/>
	</form>