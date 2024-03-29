<?php 
function query_one(){  
	return call_user_func_array(['\classes\query','one'],func_get_args() ); 
}
function query_all(){  
	return call_user_func_array(['\classes\query','all'],func_get_args() ); 
}
function query_page(){  
	return call_user_func_array(['\classes\query','page'],func_get_args() ); 
}
function theme_url(){
	return View::theme();
}
/**
  判断是否Ajax请求
*/
function is_ajax(){
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
    {
        return true;
    }
    else
    {
        return false;
    }
}
/**
	刷新当前页面
*/
function refresh(){
	header("Refresh:0");
}
/**
	创建URL
*/
function url($url , $par = []){
	$url = str_replace('.','/',$url);
	$n = substr_count($url,'/');
	if($n==2){
		$ar = explode('/',$url);
		if($ar[2]=='index'){
			$url = substr($url,0,strrpos($url,'/'));
		}
	}
	return Route::url($url,$par);
}
/**
   生成加载参数的URL，接受参数需要使用 url_decode
*/
function eurl($url , $par = []){
	if($par){
		$u['e'] = url_encode($par);
	}
	return Route::url($url,$u);
}
function url_encode($par = []){
	return urlencode(Crypt::encode(json_encode($par)));
}
function url_decode($string){
	if(!$string){
		throw new \Exception(__('URI string not exists,maybe something wrong.please contact us!'));
	}
	$obj = json_decode(Crypt::decode(urldecode($string)));
	if(!$obj){
		 throw new \Exception(__('URI params is error,maybe something wrong.please contact us!'));
	}
	return $obj;
}
/**
	取得当前HOST http://yuetaichi.com 
*/
function host(){
	return Route::init()->host;
}
/**
	URL是对public 的而言
	返回URL 如 / 或 web/public/
*/
function base_url(){ 
	return Route::init()->base_url; 
} 
/**
  翻译
*/
function __($key,$alias='app'){ 
	return Lang::get($key,$alias); 
}  
function redirect($url){
	header("location:$url"); 
	exit;
} 
function dump($str){
	print_r('<pre>');
	print_r($str);
	print_r('</pre>');
}   
function ip() {
	if(!empty($_SERVER['HTTP_CLIENT_IP'])){
	   $ip = $_SERVER['HTTP_CLIENT_IP'];
	}else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
		$arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos    =   array_search('unknown',$arr);
        if(false !== $pos) unset($arr[$pos]);
        $ip     =   trim($arr[0]);  
	}else{
	   $ip= $_SERVER['REMOTE_ADDR'];
	}
    return $ip;
}
//取得类所在的物理路径
function file_name($class){
	$reflector = new \ReflectionClass($class);
	return  $reflector->getFileName();
}
function widget($name , $par = []){ 
	return Widget::init("widget\\".$name."\\".$name,$par);
}

function import($file){ 
	static $statics;
	if(!isset($statics[$file])){
		include $file;
		$statics[$file] = true;
	} 
}
function core_path(){
 	return __DIR__;
}
 
//从项目目录加载文件
function load($file){
	$file = str_replace('.','/',$file);
	$file = str_replace('\\','/',$file);
	$file = base_path().'/'.$file.".php";
	import($file);
}

//生成cck hook列表链接
function hook_action($nid=null,$name,$method){
	$data = $_GET;
	$data['nid'] = $nid;
	$data['action'] = $name.'.'.$method;
	if($nid)
		return url('content/node/do',$data);
	unset($_GET['nid'],$_GET['action']);
	return url('content/node/index',$_GET);
}

 