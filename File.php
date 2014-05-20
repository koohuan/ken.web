<?php  
/**  
	File 操作  
	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
*/
namespace Ken\Web; 
class File
{  
 	static $obj = []; 
 	
	/**
		复制整个目录到 $to 下	
		没有返回值
		给Widget 提供 assets 复制目录功能
	*/
	static function cpdir($dir , $to ,$name = null){
		if($name) $to = $to.'/'.$name; 
	 	if(!is_dir ($dir )){
	 		return false;
	 	}   
 	 	$ar = static::find($dir);  
 	 	if(is_dir($to)) return false; 
 	  	if($ar['dir']){
	 	 	foreach($ar['dir'] as $v){
	 	 		$v = $to.''.str_replace($dir,'',$v);
	 	 		@mkdir($v,0775,true); 
	 	 	}
 	 	}
 	 	if($ar['file']){
	 	 	foreach($ar['file'] as $v){ 
	 	 		$new = $to.''.str_replace($dir,'',$v);
	 	 		@copy($v,$new);
	 	 	} 
 	 	} 
	}
	/**
		查看目录下的所有目录及文件 
	*/ 
	static function find($dir,$find='*'){
		$ar = static::__find($dir,$find);   
 	 	static::$obj = [];
 	    return $ar;
	} 
	/**
		查看目录下的所有目录及文件
		内部使用
	*/ 
	static function __find($dir_path,$find='*'){
		static::$obj['dir'][] = $dir_path;
		foreach(glob($dir_path."/*") as $v){ 
			if(is_dir($v)){
				static::$obj['dir'][] = $v;
				static::__find($v,$find);
			}else{
				static::$obj['file'][] = $v;
			} 
		}    
	 	return static::$obj;
	}
	
	/**
     	删除目录
     */
    static function rmdir($dir)
    { 
        if(strtolower(substr(PHP_OS, 0, 3))=='win'){
        	$dir = str_replace('/','\\',$dir);
        	$ex = "rmdir /s/q ".$dir;
        }
        else{
        	$dir = str_replace('\\','/',$dir);
        	$ex = "rm -rf ".$dir;   
        } 
        @exec($ex);
        
    }
    /**
    	打开PDF
    */
    function pdf($file ,$filename='1.pdf' ){ 
		header('Content-type: application/pdf');
		header('Content-Disposition: inline; filename="' . $filename . '"');
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: ' . filesize($file));
		header('Accept-Ranges: bytes'); 
		@readfile($file);
	}
	/**
	* full name
	* upload/1.jpg
	*/
	static function name($name){ 
		return substr($name,0,strrpos($name,'.')); 
	}
	/**
		返回后缀 如.jpg 
	*/
	static function ext($url){
		return substr($url,strrpos($url,'.')+1);
	}
	/**
		文件目录
	*/
	static function dir($file_name){ 
		return substr($file_name,0, strrpos($file_name,'/'));
	}
	/**
	 文件大小
	*/
	static function size($file) {
		 $filesize =  filesize($file);
		 if($filesize >= 1073741824) {
		  	$filesize = round($filesize / 1073741824 * 100) / 100 . ' gb';
		 } elseif($filesize >= 1048576) {
		  	$filesize = round($filesize / 1048576 * 100) / 100 . ' mb';
		 } elseif($filesize >= 1024) {
		 	 $filesize = round($filesize / 1024 * 100) / 100 . ' kb';
		 } else {
		 	 $filesize = $filesize . ' bytes';
		 }
		 return $filesize;
	}
 
   
   
}