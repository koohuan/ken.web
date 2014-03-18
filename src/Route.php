<?php
/**
 	可使用F注册
 	URI Route 路由 restful 格式 
 	对结构为
 	app
 		pay
 			paypal.php
 			
 	webroot -- web根目录
 	的支持
 	如果没有route . url('pay/paypal/index')
 	配置route 第三个参数 $name 此时不能有
 	$route->all('paypal','app\pay\paypal@index');
 	
 	use PHP\Classes\Route;
	$route = new Route;
	
	$route->get('/',function(){
		echo 1;
	});
	
	$route->all('login/<name:\w+>','app\login\$name@index','login');  
	
	//aa 为url地址，home为生成url链接所用的名称 	
	$route->get('aa',"app\controller\index@index",'home'); 
	
	$route->get('post/<id:\d+>/<g:\d+>',"app\controller\index@test",'post');
	
	$route->get('payadmin','app\pay\admin@list');
	$route->get('payadmin/<page:\d+>','app\pay\admin@list');

	生成URL
	echo $route->url('post',['id'=>1,'d'=>3]);
	
	//执行路由
	
	try { 
		$route->run(); 
	}catch (Exception $e) { 
		if($e->getCode() == 404) {
	     	throw new \Exception('404 page not find');
	    } else{
	    	dump($e->getMessage());
	    }
	} 
 	
 	get/post/all 方法支持
 	
 	apache 	.htaccess 配置 
 	
 	RewriteEngine On 
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteRule ^ index.php [L]
 	
	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014
*/
namespace Ken\Web;
use Closure;
class Route{
	//基础URL
	public $base_url;
	protected $method;
	static $router;  
	public $match = '/<(\w+):([^>]+)?>/';
	static $app = [];
	//相对URL
	static $index;
	static $r = 'module'; //默认路由模块
 	/** 
	get('aa',function(){});
	*/
	protected $_url;//当前正则的URL 如 aa
	protected $_value; //当前URL的function 如 function(){}  
	public $host;
	public $class = [];
	function __construct(){
		//请求方式 GET POST
 		$this->method = $_SERVER['REQUEST_METHOD'];  
 		$top = 'http';
 		if($_SERVER['SERVER_PORT'] == 443 || $_SERVER['HTTPS'] == 1 ||$_SERVER['HTTPS'] == 'on')
 			$top = 'https';
 		$this->host = $top."://".$_SERVER['SERVER_NAME']; 
 	 
 	}  
 	/**
  		对GET POST all 设置router
 	*/
	protected function set_router($url,$do,$method='GET',$name=null){   
 		if(!is_object($do) && !$name){
			$n = str_replace('@','/',$do);
			$new = str_replace('\\','/',$n);
			$name = substr($new , strpos($new,'/')+1); 
		}
 		if(strpos($url,'<')!==false){
			$url = "#^\/{$url}\$#";
		}elseif(substr($url,0,1)!='/'){
			$url = '/'.$url;
		} 
		static::$router[$method][$url] = $do;
		if($name)
			static::$router['__#named#__'][$name] = $url;
		 
	}
	/**
		自动生成URL
	*/
	function url($url,$par = []){
		$id = 'route_url'.$url.json_encode($par);
		if(static::$app[$id]) return static::$app[$id];
		$r = static::$router['__#named#__'][$url]; 
		preg_match_all($this->match, $r, $out);
		//[<id:\d+>]
		$a = $out[0];
		//['id']
		$b = $out[1];
		if($b){
			$i = 0;
			foreach($b as $v){
				$r = str_replace($a[$i],$par[$v],$r);
				unset($par[$v]);
				$i++;
			}
		}  
		if(substr($r,0,2) == '#^')
			$r = substr($r,4,-2);  
		if(substr($r,-1)=='/')
			$r = substr($r,0,-1);  
		if(!$r) $r = $url;  
		if($par) 
			$r = $r."?".http_build_query($par);  
 		$url = $this->base_url.$r;
 		$url = str_replace("//",'/',$url);
 		static::$app[$id] = $url;
	 	return $url;	 
	}
 
	// get request
	function get($url,$do,$name=null){
		$this->set_router($url,$do,'GET',$name);
		return $this;
	}
	// post request
	function post($url,$do,$name=null){
		$this->set_router($url,$do,'POST',$name);
		return $this;
	}
	// put request
	function put($url,$do,$name=null){
		$this->set_router($url,$do,'PUT',$name);
		return $this;
	}
	// put request
	function delete($url,$do,$name=null){
		$this->set_router($url,$do,'DELETE',$name);
		return $this;
	}
	// get/post request
	function all($url,$do,$name=null){
		$this->set_router($url,$do,'POST',$name); 
		$this->set_router($url,$do,'GET',$name); 
		return $this;
	}
	/**
		执行解析URL 到对应namespace 或 closure 
	*/
	function run(){  
		//解析URL $uri 返回 /app/public/ 或  / 
		$uri = $_SERVER['REQUEST_URI']; 
		$uri = str_replace($this->host,'',$uri);
		if(strpos($uri,'?')!==false)
			$uri = substr($uri,0,strpos($uri,'?'));
		//取得入口路径
		$index = $_SERVER['SCRIPT_NAME'];
		$index = substr($index,0,strrpos($index,'/')); 
		$action = substr($uri,strlen($index)); 
	 	$this->base_url = $index?$index.'/':'/'; 
	 	/**
	 		对于未使用正则的路由匹配到直接goto
	 	*/
		$this->_value = static::$router[$this->method][$action]; 
		$data = [];
		if($this->_value) goto TODO; 
		foreach(static::$router[$this->method] as $pre=>$class){  
			if(preg_match_all($this->match, $pre, $out)){
				//转成正则   
                foreach($out[0] as $k=>$v){ 
                	$pre = str_replace($v,"(".$out[2][$k].")",$pre);
                }  
                $pregs[$pre] = ['class'=>$class,'par'=>$out[1]]; 
			} 
		}
		/**
			匹配当前URL是否存在路由
		*/ 
		if($pregs){
			foreach($pregs as $p=>$par){ 
				$class = $par['class'];
				if(preg_match($p,$action, $new)){ 
					unset($new[0]);
	               	//根据请求设置值 $_POST $_GET
	                $data = $this->set_request_value($this->array_combine($par['par'],$new));    
	                $this->_url = $pre;
	                $this->_value = $class;  
	                goto TODO;  
                } 
               
			}
		} 
	 	if($this->_value){ 
	 		TODO:
	 		// 如果是 closure 
	 		if(is_object($this->_value) && ($this->_value instanceof Closure) )
	 			return call_user_func_array($this->_value,$data); 
	 		// 对 namespace 进行路由
	 		$cls = explode('@',$this->_value);   
 			$class = $cls[0];
 			if($data){
 				// $route->get('aa',"app\controller\index@index",'home'); 
 				foreach($data as $k=>$v){
 					$class = str_replace("$".$k,$v,$class);
 				}
 			} 
 			$ac = $cls[1];
 			$this->class = [$class,$ac];
 			$this->class_exists($class,$ac);
 			$obj = new $class;  
			return call_user_func_array([$obj,$ac."Action"],$data);   
	 	} 
 	 	//加载app\admin\login.php 这类的自动router 
	 	try{
	 		$action = trim(str_replace('/',' ',$action));
		 	$a = explode(' ',$action);
		 	$class = static::$r."\\".$a[0]."\\".$a[1];
		 	$ac = $a[2]?:'index'; 
		 	$this->class = [$class,$ac]; 
		 	$this->class_exists($class,$ac);
		 	$obj = new $class;  
	 		return call_user_func_array([$obj,$ac."Action"],$data); 
	 	}catch (Exception $e){  
	 		throw new \Exception('404 page not find',404);
	 	}
	 
	}
	protected function class_exists($class,$ac){
		if(!class_exists($class)) throw new \Exception(' Request IS Not Exists',400);
	 	if(!method_exists($class,$ac."Action")) throw new \Exception("Action  not exists",400);
	}
 
	/**
		对 array_combine
		$a ['id','name']
		$b [1,'test']
	*/
	protected function array_combine($a=[],$b=[]){   
		 $i = 0;
		 foreach($b as $v){
		 	$out[$a[$i]] = $v;
		 	$i++;
		 } 
		 return $out; 
	}
	/**
		根据请求设置值
	*/
	protected function set_request_value($data){  
			switch($this->method){
           		case 'GET': 
           			$_GET = array_merge($data,$_GET);
           			break;
           		case 'POST':
           			$_POST = array_merge($data,$_POST);
           			break;
           		case 'PUT':
           			$_PUT = array_merge($data,$_PUT); 
           			break;
           		case 'DELETE':
           			$_PUT = array_merge($data,$_DELETE); 
           			break;
           	} 
           	return $data;
	}
}