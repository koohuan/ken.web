文件操作
========
src/File.php

复制整个目录到 $to 下	
没有返回值
给Widget 提供 assets 复制目录功能
如 $dir = '/assets'; $to="assets"; $name = 'bootstrap';
$dir将被复制到 $to.'/'.$name下
	
	\File::cpdir($dir , $to ,$name);

查看目录下的所有目录及文件

	\File::loop($dir);

删除目录

	\File::rmdir($dir);