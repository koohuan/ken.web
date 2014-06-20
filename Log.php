<?php
/**
  	Log
 	
 	//启用日志,无参数时将启用所有级别的日志，如为数组将只启用对应的日志
	Log::open(['test']);  

 	系统级别重要日志  mongodb 日志
 	\Log::mo([
		'uri' =>$_SERVER['REQUEST_URI'], 
		'create_at' => date('Y-m-d H:i:s')
	],'request'); 
 	 
 
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
	static $open = false;
	static $enable;
	static $object;
	static function colose(){
		static::$open = false;
	}
	/**
	* 启用日志
	*/
	static function open($arr = null ){
		if(!isset(static::$object)){
			static::init();
			static::$object = true;
		}
		static::$open = true;
		if($arr)
			static::$enable = $arr;
	}
	//系统级日志，记录到Mongo DB中
 	static function mo( $arr = [] , $leavel = 0){
 		if(!implode('',$arr)) return;
 		if(true === Mo::w('log')->active)
 			Mo::w('log')->insert('log_'.$leavel , $arr);
 		else{
 			static::$enable['mo_'.$leavel] = 'mo_'.$leavel;
 			static::write('mo_'.$leavel,$arr,true);
 		}
 	}
 	static function init(){
 		$path = Config::get('app.log');
 		if(!$path) $path = base_path().'/temp/logs'; 
 		if(!is_writable($path)){
	 		$root = substr($path,0,strrpos($path,'/'));
	 		exec("chmod 777 $root");
 		}  
 		if(!is_dir($path)) { 
 			mkdir($path, 0777, true);
 		} 
 		static::$path = realpath($path);  
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
			foreach(array_reverse($list['file']) as $v){
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
 	static function json($arr , $name = null){
 		static::write($name,json_encode($arr));
 	}
 	//写文件
 	static function write($type = 'info',$str ,$w = false){ 
 		if(!$str) return ; 
 		if(false === $w && static::$open !== true) return ;
 		if(static::$enable && !in_array(strtolower($type),static::$enable)) return; 
 		$type = ucfirst($type);
 		$dir = static::$path.'/'.$type.'/'.date("Y").'/'.date('m');
 		if(!is_dir($dir)) {
 			if (!mkdir($dir, 0777, true)) { 
 				static::init(); 
		    }
 		}
  		$filename = $dir.'/'.date("dH").".log";
  		if(is_object($str )) $str  = Arr::object2array($str) ; 
 		if(is_array($str)) {
 			unset($new);
 			foreach($str as $k=>$v){
 				$k1[] = $k;
 				$v1[] = $v; 
 			}
 			if(!file_exists($filename)){
	 			foreach($k1 as $v){
	 				if(is_object($v)) $v= (array)$v;
	 				if(is_array($v)) $v = json_encode($v);
	 				$new .= $v."\t"; 
	 			}
	 			$new .= "\r";
 			}
 			foreach($v1 as $v){
 				if(is_object($v)) $v= (array)$v;
 				if(is_array($v)) $v = json_encode($v);
 				$new .= $v."\t"; 
 			} 
 			$str = $new;
 		}  
 		if(!$str) return;  
 		try{
 			$fh = fopen($filename, "a+");
 			$str = $str."\t runtime:".date('i:s')."\n";
			fwrite($fh, $str);
			fclose($fh);
 		}catch(Exception $e) { 
		    
		} 
		 
 	}
 	
 	static function __callStatic ($name ,$arg = [] ){ 
 		 if(strtolower(substr($name,0,4))=='json'){ 
 		 	$name = substr($name,4); 
 		 	static::json($arg[0],$name);
 		 	return ;
 		 }
 		 static::write($name , $arg[0]);
	}
 	  
}