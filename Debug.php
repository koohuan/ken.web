<?php 
/**
    //debug  
	if(Config::get('app.debug') === true)
		new Ken\Web\Debug(); 

 	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
 */
namespace Ken\Web;
class Debug
{ 
	public $profiler;
	static $log = false;
	
	/**
	
	*/
	static function mtime(){
		$time = microtime();
		$time = explode(' ', $time);
		$time = $time[1] + $time[0];
		$start = $time;
		return $start;
	}
	/**
	Debug::rtime((Debug::mtime()-$start)*1000)
	*/
	static function rtime($time) {
		$ret = $time;
		$formatter = 0;
		$formats = array('ms', 's', 'm');
		if($time >= 1000 && $time < 60000) {
			$formatter = 1;
			$ret = ($time / 1000);
		}
		if($time >= 60000) {
			$formatter = 2;
			$ret = ($time / 1000) / 60;
		}
		$ret = number_format($ret,3,'.','') . ' ' . $formats[$formatter];
		return $ret;
	}

	public function __construct() {
		import(__DIR__.'/vendor/PHP-Quick-Profiler/classes/Console.php'); 
		import(__DIR__.'/vendor/PHP-Quick-Profiler/classes/PhpQuickProfiler.php'); 
        $this->profiler = new \PhpQuickProfiler(\PhpQuickProfiler::getMicroTime());
        DB::w()->debug = true;
        static::$log = true;
    }   
    public static function log($str){
    	if(static::$log !== true ) return;
    	\Console::log($str); 
	}
	public static function logm($str){ 
		if(static::$log !== true ) return;
		 \Console::logMemory($this, $str.' : Line '.__LINE__);
	}
	public static function logs($str){ 
		if(static::$log !== true ) return;
		\Console::logSpeed($str.' '.__LINE__);
	}
	public function display() {
		$this->profiler->display();
	}
	
 
}