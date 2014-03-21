<?php
/**  
  	Curl  注意当 https://请求时，如果不小心在url前有空格会有问题，
 	所以该类trim($url) 解决这个问题
 	
 	use PHP\Classes\Curl;
 	$curl = new Curl;
 	$curl->get($url);
	$curl->post($url,$data = []); 
	$curl->close();
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
 
class Curl{
 	public $option;
	public $agent = "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.66 Safari/537.36";
	public $timeout = 300; 
	public $curl;
	public $data;
	public $info;
	public $connect = 120;
	public function __construct(){ 
		if(!extension_loaded ('curl')){ 
    		throw new \Exception('CURL not installed');
    	}
		$this->curl = curl_init();  
 		$this->option[CURLOPT_TIMEOUT] = $this->timeout;
 		$this->option[CURLOPT_CONNECTTIMEOUT] = $this->connect;
 		 
 		$this->option[CURLOPT_SSL_VERIFYPEER] = false;
 		$this->option[CURLOPT_SSL_VERIFYHOST] = false; 
 		$this->option[CURLOPT_NOBODY] = false;   
 		$this->option[CURLOPT_USERAGENT] = $this->agent;  
 		//要求结果为字符串且输出到屏幕上 
 		$this->option[CURLOPT_RETURNTRANSFER] = 1; 
	}
		   
 	public function set($type,$value){
 		$this->option[$type] = $value;
 	}
 	/**
 	 get curl
 	*/
 	function get($url){ 
 		return $this->run($url);
 	}
 	/**
 	  post curl
 	*/
 	function post($url,$data){ 
 		$this->option[CURLOPT_POST] = true;
 		$this->option[CURLOPT_POSTFIELDS] = $data;
 		$this->option[CURLOPT_CUSTOMREQUEST] = "POST"; 
 		//解决 POST 数据过长问题
 		$this->option[CURLOPT_HTTPHEADER] = ['Expect:'];   
 		return $this->run($url);
 	} 
 	function close(){
 		curl_close($this->curl); 
 	}
    protected function run($url){  
 		curl_setopt($this->curl,CURLOPT_URL,trim($url));  
 		foreach($this->option as $k=>$v){  
 			curl_setopt ( $this->curl , $k, $v);
 		}
 		$this->data = curl_exec($this->curl);  
 		if (curl_errno($this->curl)) {
 			throw new \Exception('CURL RUN ERROR'.curl_error($this->curl)); 
		}
		$this->info = curl_getinfo($this->curl);  
 		return $this->data;
 	}  
 	function get_data(){
 		return $this->data();
 	}
 	function get_info(){
 		return $this->info();
 	}
 	 
 	  
}