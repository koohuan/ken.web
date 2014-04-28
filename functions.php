<?php
use Ken\Web\F;
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
	return Route::url($url,$par);
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
	$file = base_path().'/'.str_replace('.','/',$file).".php";
	import($file);
}
