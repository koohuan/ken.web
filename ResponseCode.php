<?php 
/** 
 	 
    
 	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
 */
namespace Ken\Web;
class ResponseCode
{  
	public static $code = [
		100 => 'Continue',
		101 => 'Switching Protocols',
		102 => 'Processing',
		118 => 'Connection timed out',
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		207 => 'Multi-Status',
		208 => 'Already Reported',
		210 => 'Content Different',
		226 => 'IM Used',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		306 => 'Reserved',
		307 => 'Temporary Redirect',
		308 => 'Permanent Redirect',
		310 => 'Too many Redirect',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Time-out',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested range unsatisfiable',
		417 => 'Expectation failed',
		418 => 'I\'m a teapot',
		422 => 'Unprocessable entity',
		423 => 'Locked',
		424 => 'Method failure',
		425 => 'Unordered Collection',
		426 => 'Upgrade Required',
		428 => 'Precondition Required',
		429 => 'Too Many Requests',
		431 => 'Request Header Fields Too Large',
		449 => 'Retry With',
		450 => 'Blocked by Windows Parental Controls',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway ou Proxy Error',
		503 => 'Service Unavailable',
		504 => 'Gateway Time-out',
		505 => 'HTTP Version not supported',
		507 => 'Insufficient storage',
		508 => 'Loop Detected',
		509 => 'Bandwidth Limit Exceeded',
		510 => 'Not Extended',
		511 => 'Network Authentication Required',
	];
	
	public $version;
	public $header;
	function __construct(){ 
		if (isset($_SERVER['SERVER_PROTOCOL']) && $_SERVER['SERVER_PROTOCOL'] === '1.0') {
			$this->version = '1.0';
		} else {
			$this->version = '1.1';
		}
	} 
	/*
		设置header 信息
	*/
	function set($key,$value){
		$this->header[$key] = $value;
	} 
	function get_code(){
		return $_SERVER['KEN_WEB_CODE'];
	}
	function get_txt(){
		return $_SERVER['KEN_WEB_CODE_TXT'];
	}
	/**
		发送header 头信息
	*/
	function code($code = 200 ,$txt = null){
		$_SERVER['KEN_WEB_CODE'] = $code;
		$_SERVER['KEN_WEB_CODE_TXT'] = $txt;
		if(!$txt)
			$txt = $this->code[$code];
		header("HTTP/{$this->version} $code {$txt}");
		if($this->header){
			foreach($this->header as $key=>$value){
				header($key.":".$value);
			}
		}
	}
	/**
		下载文件
	*/
	function download($file){
		$info = pathinfo($file);
		$this->set('Content-type','application/x-'.$info['extension']);
		$this->set('Content-Disposition','attachment; filename='.$info['basename']);
		$this->set('Content-Length',filesize($file)); 
		$this->status();
		readfile($file);
	}
	

	
 
}