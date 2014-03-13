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
	return F::get('route')->url($url,$par);
}
/**
	ȡ�õ�ǰHOST http://yuetaichi.com 
*/
function host(){
	return F::get('route')->host;
}
/**
	URL�Ƕ�public �Ķ���
	����URL �� / �� web/public/
*/
function base_url(){ 
	return F::get('route')->base_url; 
} 
/**
  ����
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