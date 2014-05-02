<?php
/**
  	Log
 	
 	系统级别重要日志
 	
 	\Log::system([
		'uri' =>$_SERVER['REQUEST_URI'], 
		'create_at' => date('Y-m-d H:i:s')
	],'request');
	
	\Log::system([
		'order_id' => $this->order_id,
		'payment_method'  => $this->id,
		'amount'			=> $this->amount,
		'create_at'	    => date('Y-m-d H:i:s'),
	],"payment");
	
 	 
	//普通文本日志
	Log::init(); 

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
	//系统级日志，记录到Mongo DB中
 	static function system( $arr = [] , $leavel = 0){
 		Mo::w()->insert('log_'.$leavel , $arr);
 	}
 	static function init(){
 		static::$path = Config::get('app.log'); 
 		if(!is_dir(static::$path)) mkdir($path , 777 ,true); 
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
 		if(is_array($str)) {
 			unset($new);
 			foreach($str as $k=>$v){
 				$new .= $k."=".$v."\n";
 			}
 			$str = $new;
 		}
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