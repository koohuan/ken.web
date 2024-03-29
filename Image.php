<?php 
/**
    http://www.fuelphp.com/docs/classes/image.html
  
    使用方法
    
    $a = public_path().'/upload/1.png';
	$b = public_path().'/upload/2.png';
	$op = ['quality'=>75];
	Image::init($op)->load($a)->output($b);
	
	Image::init($op)->load($a)->save($b);
    
    
    $url 来自数据库 files表中的url字段
 	$op = [
		'bgcolor' => '#f00', 
        'quality' => 50,
        'actions' =>[
            'resize'=>[200, 180], 
        ]
	]; 
	$u = \Image::set($url,$op);  
	
 	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
*/
namespace Ken\Web;
class Image extends \Ken\Web\Vendor\FuelImages{
	/**
 	$url 来自数据库 files表中的url字段
 	$op = [
		'bgcolor' => '#f00', 
        'quality' => 50,
        'actions' =>[
            'resize'=>[200, 180], 
        ]
	]; 
	$u = \Image::set($url,$op);  
 	*/
 	static function set($url , $op = []){ 
 		$set = $op; //保存到image_cache
 		$u = substr($url,0,strrpos($url,'/'));
 		$name = substr($url,strrpos($url,'/')+1);
 		$name = substr($name,0,strrpos($name,'.'));
 		$real = 'upload/image'.substr($u,strpos($u,'/'));
 		$dir = public_path().'/'.$real;
 		if(!is_dir($dir)) mkdir($dir,0775,true);
 		$file_name = $name.'_'.substr(md5(json_encode($op).$url),8,16).".".File::ext($url);
 		$a = public_path().'/'.$url; 
 		if(!file_exists($a)) return null;
 		$b = $dir.'/'.$file_name; 
 		$c = $real.'/'.$file_name;   
 		if(!file_exists($b)){
 			$ac = $op['actions'];
 			unset( $op['actions'] );
 			$im = \Image::init($op)->load($a);
 			foreach($ac as $k=>$v){ 
 				$im = call_user_func_array([$im,$k],$v);
 			}
 			$im->save($b); 
 			// md5 name
	 		$name = md5($c);
	 	    if(!\DB::w()->from('image_cache')->where('name=?',$name)->one()){
	 	    	\DB::w()->insert('image_cache',[
	 	    		'name'=>$name,
	 	    		'set'=>serialize($set),
	 	    		'url'=>$url,
	 	    	]);
	 	    }
 		} 
 		
 		return $c;
 	}
 	//从数据库中取得配置，再次生成图片
 	static function cache($c){
 		$name = md5($c); 
	 	$one = \DB::w()->from('image_cache')->where('name=?',$name)->cache()->one();
	 	if(!$one) return;
	 	$set = unserialize($one->set); 
	 	$url = $one->url;  
	 	$id = static::set($url,$set); 
	 	echo @file_get_contents(public_path().'/'.$id);
 	}
 	//取得经缩放等处理图片URL的原URL
 	static function get($url){ 
 		$u = substr($url,0,strrpos($url,'/')+1);
 		$u = str_replace('/image/','/',$u);  
 		$name = substr($url,strrpos($url,'/')+1);
 		$name = substr($name,0,strrpos($name,'_')); 
 		return $u.$name.'.'.File::ext($url);
 	}
}