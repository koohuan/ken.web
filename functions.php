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
/**
  �ж��Ƿ�Ajax����
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
	ˢ�µ�ǰҳ��
*/
function refresh(){
	header("Refresh:0");
}
/**
	����URL
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
   ���ɼ��ز�����URL�����ܲ�����Ҫʹ�� url_decode
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
	ȡ�õ�ǰHOST http://yuetaichi.com 
*/
function host(){
	return Route::init()->host;
}
/**
	URL�Ƕ�public �Ķ���
	����URL �� / �� web/public/
*/
function base_url(){ 
	return Route::init()->base_url; 
} 
/**
  ����
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
//����ĿĿ¼�����ļ�
function load($file){
	$file = str_replace('.','/',$file);
	$file = str_replace('\\','/',$file);
	$file = base_path().'/'.$file.".php";
	import($file);
}

//����cck hook�б�����
function hook_action($nid=null,$name,$method){
	$data = $_GET;
	$data['nid'] = $nid;
	$data['action'] = $name.'.'.$method;
	if($nid)
		return url('content/node/do',$data);
	unset($_GET['nid'],$_GET['action']);
	return url('content/node/index',$_GET);
}
 