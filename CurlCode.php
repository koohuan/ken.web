<?php
/**  
	
	$curl = \Curl::init();
 	$curl->header = true;
	$g = $curl->get($url);
	Curl::info();
	
	//////////////////////////////////////////////
	
	Curl::get($url)->get_data();
	Curl::post($url,$data)->get_data();
	get_data();
	get_info();
	set 设置参数紧跟Curl::
	
	
  	Curl  注意当 https://请求时，如果不小心在url前有空格会有问题，
 	所以该类trim($url) 解决这个问题 
	更多选项设置：	
	CURLOPT_HEADER 
	CURLOPT_NOBODY
	CURLOPT_REFERER
	CURLOPT_COOKIEJAR
	CURLOPT_COOKIEFILE
	CURLOPT_RETURNTRANSFER
	CURLOPT_FOLLOWLOCATION
	CURLOPT_COOKIE: 传递一个包含HTTP cookie的头连接。 
	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
*/
namespace Ken\Web;
 
class CurlCode{
 	public $option;
	public $agent = "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.66 Safari/537.36";
	public $timeout = 300; 
	private $curl;
	public $data;
	static $info;
	public $connect = 120;
	public $header = false;
	public $cookie = false;
	public $cookie_file;
	public $nobdy = false;
 	public function __construct(){ 
		if(!extension_loaded ('curl')){ 
    		throw new \Exception('CURL not installed');
    	}
		$this->curl = curl_init();  
 		$this->option[CURLOPT_TIMEOUT] = $this->timeout;
 		$this->option[CURLOPT_CONNECTTIMEOUT] = $this->connect;
 		$this->option[CURLOPT_SSL_VERIFYPEER] = false;
 		$this->option[CURLOPT_SSL_VERIFYHOST] = false;   
 		$this->option[CURLOPT_SSLVERSION] = 3;  
 		$this->option[CURLOPT_USERAGENT] = $this->agent;  
 		//返回字符串，而非直接输出
 		$this->option[CURLOPT_RETURNTRANSFER] = 1; 
 		if(true === $this->cookie){
 			$this->cookie_file = tempnam('./tmp','CURLSESSIONID'); 
 			if(!file_exists($this->cookie_file))
 				$this->option[CURLOPT_COOKIEJAR] = $this->cookie_file;
 			else
 				$this->option[CURLOPT_COOKIEFILE] = $this->cookie_file;
 		} 
 		
	}

 	public function set($type,$value){
 		$this->option[$type] = $value;
 	}
 	function get($url,$options = []){ 
 		return $this->_get($url,$options)->get_data();
 	}
 	function post($url,$data ,$options = []){ 
 		return $this->_post($url,$data ,$options)->get_data();
 	}
 	function geth($url,$options = []){ 
 		$this->header = true;
 		$this->nobdy = true; 
 		return $this->_get($url,$options)->get_data();
 	}
 	function posth($url,$data ,$options = []){ 
 		$this->header = true;   
 		$this->nobdy = true; 
 		return $this->_post($url,$data ,$options)->get_data();
 	}

 	/**
 	 get curl
 	*/
 	private function _get($url,$options = []){ 
 		if($options){
 			foreach($options as $k=>$v){
 				$this->option[$k] = $v;
 			}
 		} 
 		return $this->run($url);
 	}
 	/**
 	  post curl
 	*/
 	private function _post($url,$data ,$options = []){ 
 		$this->option[CURLOPT_POST] = true;
 		$this->option[CURLOPT_POSTFIELDS] = $data;
 		$this->option[CURLOPT_CUSTOMREQUEST] = "POST"; 
 		//解决 POST 数据过长问题
 		$this->option[CURLOPT_HTTPHEADER] = ['Expect:'];   
 		if($options){
 			foreach($options as $k=>$v){
 				$this->option[$k] = $v;
 			}
 		} 
 		return $this->run($url);
 	} 
  
    private function run($url){      
    	if(true === $this->header)
    		$this->option[CURLOPT_HEADER] = 1; 
 		curl_setopt($this->curl,CURLOPT_URL,trim($url));   
 		foreach($this->option as $k=>$v){  
 			curl_setopt ( $this->curl , $k, $v);
 		}    
 		curl_setopt ( $this->curl , CURLOPT_NOBODY, $this->nobdy);
 		$this->data = curl_exec($this->curl);  
 		static::$info = curl_getinfo($this->curl);  
 		if (curl_errno($this->curl)) {
 			throw new \Exception(curl_error($this->curl)); 
		} 
		curl_close($this->curl);
 		return $this;
 	}   
 	
 	public function get_data(){
 		return $this->data;
 	}
 	static function info(){
 		return static::$info;
 	}
 	 
 	  
}