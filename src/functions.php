<?php
use Ken\Web\F;
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
	return F::get('route')->url($url,$par);
}
/**
	取得当前HOST http://yuetaichi.com 
*/
function host(){
	return F::get('route')->host;
}
/**
	URL是对public 的而言
	返回URL 如 / 或 web/public/
*/
function base_url(){ 
	return F::get('route')->base_url; 
} 
/**
  翻译
*/
function __($key,$alias='app'){ 
	return F::get('lang')->get($key,$alias); 
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
    return $_SERVER['REMOTE_ADDR'];
}
function widget($name , $par = []){ 
	return F::get('widget')->push("widget\$name\$name",$par);
}