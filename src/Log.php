<?php
/**
  	Log
 	
 	 
	Log::start(__DIR__.'/../temp/logs');
	Log::info('test');
 	Log::error('test');
 	Log::read();

	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
*/
namespace Ken\Web;
 
class Log{
	static $path;  
	//是否开启日志 ，默认开启
	static $log = true;
	function __construct($path){ 
 		$this->path = $path;  
 	}
 	static function start($path){
 		static::$path = $path;
 		if(!is_dir($path)) mkdir($path , 777 ,true);
 		 
 	}  
 	//读取所有日志
 	static function read(){
 		$list = scandir(static::$path);
		foreach($list as $vo){   
			if($vo !="."&& $vo !=".." && $vo !=".svn" )
			{ 
				$out[$vo] = @file_get_contents(static::$path.'/'.$vo);
			}
		}
 		return $out;
 	}
 	//清空日志
 	static function clean(){
 		$list = scandir(static::$path);
		foreach($list as $vo){   
			if($vo !="."&& $vo !=".." && $vo !=".svn" )
			{ 
				@unlink(static::$path.'/'.$vo);
			}
		}
 	}
 	//写info
 	static function info($str){
 		static::write('info',$str);
 	}
 	//写错误信息
 	static function error($str){
 		static::write('error',$str);
 	}
 	//写文件
 	static function write($type = 'info',$str){
 		if(static::$log !== true) return ;
 		if(!$str) return;
 		$str = "$type: ".$str."   ".date('H:i:s',time())."\n";
 		$filename = static::$path.'/'.$type.'-'.date("Y-m-d").".log";
 		try{
 			$fh = fopen($filename, "a+");
			fwrite($fh, $str);
			fclose($fh);
 		}catch(Exception $e) { 
		    throw new \Exception('write log failed');
		} 
		 
 	}
 	  
}