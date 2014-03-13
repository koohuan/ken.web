<?php 
/**
  	内容正则取图片
  
	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
 */
namespace Ken\Web; 
class Img
{ 
 	/**
 	* 本地的图片
 	* 如果存在返回图片的URL
 	*/
	function local_one($str){
		return $this->local($str , false);
	}
	/**
 	* 本地的所有图片
 	* 返回数组，元素为URL
 	*/
	function local_all($str){
		return $this->local($str , true);
	}
	/**
	* 不区别本地或线上图片
	* 返回第一个图片的URL
	*/
	function get_one($str){
		return $this->get($str , false);
	}
	/**
	* 不区别本地或线上图片
	* 数组格式 返回所有图片的URL
	*/
	function get_all($str){
		return $this->get($str , true);
	}
	/**
	* 移除内容中的图片元素
	*/
	function remove($content){  
		$preg = '/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i';
		$out = preg_replace($preg,"",$content);
		return $out;
	} 
	/**
	* 图片的宽高
	*/
	function wh($img){
		$a = getimagesize(root_path().$img);
		return array('w'=>$a[0],'h'=>$a[1]);
	}
 
	protected function get($content,$all=true){ 
		$preg = '/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i'; 
		preg_match_all($preg,$content,$out);
		$img = $out[2];  
		if($all === true){
			return $img;
		}else if($all === false){
			return $img[0]; 
		}
		return $out[0];
	} 
	function local($content,$all=false){  
		$img = $this->get($content, true);
		if($img) { 
			$num = count($img); 
			for($j=0;$j<$num;$j++){ 
				$i = $img[$j]; 
				if( (strpos($i,"http://")!==false || strpos($i,"https://")!==false ))
				{
					unset($img[$j]);
				}
			}
		}
		if($all === true){
			return $img;
		}
		return $img[0]; 
	} 
	
 
}