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
 	
 	Route:
 	Route::get('log',function(){
		$r = Log::read();
		dump($r);
	});
	Route::get('clean',function(){
		Log::clean();
		 
	});

	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
*/
namespace Ken\Web;
 
class Log{
	static $path;  
	//是否开启日志 ，默认开启
	static $open = true;
	//系统级日志，记录到Mongo DB中
 	static function mo( $arr = [] , $leavel = 0){
 		if(!implode('',$arr)) return;
 		if(true === Mo::w('log')->active)
 			Mo::w('log')->insert('log_'.$leavel , $arr);
 		else
 			static::write('mo_'.$leavel,$arr,true);
 	}
 	static function init(){
 		$path = Config::get('app.log');
 		if(!$path) $path = base_path().'/temp/logs'; 
 		static::$path = realpath($path);    
 		if(!is_dir(static::$path)) mkdir(static::$path , 777 ,true); 
 	}  
 	//读取所有日志
 	static function read($name = null){
 		$dir = static::$path;
 		if($name){
 			$name = ucfirst($name);
 			$dir = $dir.'/'.$name;
 		} 
 		$list = File::find($dir);
		if($list['file']){
			foreach($list['file'] as $v){
				$k = str_replace(static::$path,'',$v);
				$content = file_get_contents($v); 
				$out .= "<h3>".$k."</h3>".$content."\n\n";
			}
		}
 		return $out;
 	}
 	//清空日志
 	static function clean($name = null){
 		$dir = static::$path;
 		if($name){
 			$name = ucfirst($name);
 			$dir = $dir.'/'.$name;
 		} 
 		$list = File::find($dir);
		if($list['file']){
			foreach($list['file'] as $v){
				unlink($v);
			}
		}
		File::rmdir($dir);
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
 	static function write($type = 'info',$str ,$w = false){
 		if(!$str) return ;
 		if(false === $w && static::$open !== true) return ;
 		$type = ucfirst($type);
 		$dir = static::$path.'/'.$type.'/'.date("Y").'/'.date('m');
 		if(!is_dir($dir)) mkdir($dir,0775,true);
  		$filename = $dir.'/'.date("dH").".log";
  		
 		if(is_array($str)) {
 			unset($new);
 			foreach($str as $k=>$v){
 				$k1[] = $k;
 				$v1[] = $v; 
 			}
 			if(!file_exists($filename)){
	 			foreach($k1 as $v){
	 				$new .= $v."\t"; 
	 			}
	 			$new .= "\r";
 			}
 			foreach($v1 as $v){
 				$new .= $v."\t"; 
 			} 
 			$str = $new;
 		}  
 		if(!$str) return;
 		$str = $str; 
 		try{
 			$fh = fopen($filename, "a+");
 			$str = $str."\t runtime:".date('i:s')."\n";
			fwrite($fh, $str);
			fclose($fh);
 		}catch(Exception $e) { 
		    throw new \Exception('write log failed');
		} 
		 
 	}
 	
 	static function __callStatic ($name ,$arg = [] ){
 		 $str = implode("\n",$arg); 
 		 static::write($name , $str);
	}
 	  
}