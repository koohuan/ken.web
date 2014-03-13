<?php
/**
 	alias 使用
 	
 	如有报错，需要设置F
	F::set('route',function(){ 
		return new Route;
	});

	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
 */
/*	
$list = scandir(__DIR__);
foreach($list as $v){ 
	if($v=='alias.php' || $v=='.' || $v=='..' || $v=='.svn' || $v=='.git') continue; 
	$n = substr($v,0,-4);
	echo "'PHP\Classes\\".$n."'=>'".$n."',\n"; 
}
exit;
*/
/**
 	alias
 	
	@auth Kang Sun <68103403@qq.com>
 	@date 2014 
*/
$alias = [ 
	'PHP\Classes\AdminController'=>'AdminController', 
 
	'PHP\Classes\Helper'=>'Helper',
	'PHP\Classes\Lang'=>'Lang',
	'PHP\Classes\Arr'=>'Arr',
	'PHP\Classes\User'=>'User',
	'PHP\Classes\Auth'=>'Auth',
	'PHP\Classes\Mail'=>'Mail',
	'PHP\Classes\Cache'=>'Cache',
	'PHP\Classes\CleanVendor'=>'CleanVendor',
	'PHP\Classes\Controller'=>'Controller',
	'PHP\Classes\Cookie'=>'Cookie',
	'PHP\Classes\Crypt'=>'Crypt',
	'PHP\Classes\Curl'=>'Curl',
	'PHP\Classes\DB'=>'DB',
	'PHP\Classes\Event'=>'Event',
	'PHP\Classes\F'=>'F',
	'PHP\Classes\File'=>'File',
	'PHP\Classes\Form'=>'Form',
	'PHP\Classes\HTML'=>'HTML',
	'PHP\Classes\Img'=>'Img',
	'PHP\Classes\Input'=>'Input',
	'PHP\Classes\Log'=>'Log',
	'PHP\Classes\Paginate'=>'Paginate',
	'PHP\Classes\Response'=>'Response',
	'PHP\Classes\Route'=>'Route',
	'PHP\Classes\Session'=>'Session',
	'PHP\Classes\Str'=>'Str',
	'PHP\Classes\Tree'=>'Tree',
	'PHP\Classes\UserController'=>'UserController',
	'PHP\Classes\Validate'=>'Validate',
	'PHP\Classes\View'=>'View',
	'PHP\Classes\Widget'=>'Widget', 
];
foreach($alias as $k=>$v){
	class_alias($k,$v);
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
/**
	URL跳转
*/
function redirect($url){
	header("location:$url"); 
	exit;
}
/**
	格式化输出
*/
function dump($str){
	print_r('<pre>');
	print_r($str);
	print_r('</pre>');
} 
 
 
 
/**
 * 获取客户端IP地址 
 * https://github.com/liu21st/thinkphp/blob/master/ThinkPHP/Common/functions.php
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @param boolean $adv 是否进行高级模式获取（有可能被伪装） 
 * @return mixed
 */
function ip($type = 0,$adv=false) {
    $type       =  $type ? 1 : 0;
    static $ip  =   NULL;
    if ($ip !== NULL) return $ip[$type];
    if($adv){
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos    =   array_search('unknown',$arr);
            if(false !== $pos) unset($arr[$pos]);
            $ip     =   trim($arr[0]);
        }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip     =   $_SERVER['HTTP_CLIENT_IP'];
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip     =   $_SERVER['REMOTE_ADDR'];
        }
    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip     =   $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u",ip2long($ip));
    $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}