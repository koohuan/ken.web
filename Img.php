<?php 
/**
  	内容正则取图片
  	
  	
  	判断图片是否为动态
	is_animated_gif
	判断图片是否是透明的
	is_alpha_png
	
	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
 */
namespace Ken\Web; 
class Img
{ 
	static function get_gravatar( $email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array() ) {
	    $url = 'http://www.gravatar.com/avatar/';
	    $url .= md5( strtolower( trim( $email ) ) );
	    $url .= "?s=$s&d=$d&r=$r";
	    if ( $img ) {
	        $url = '<img src="' . $url . '"';
	        foreach ( $atts as $key => $val )
	            $url .= ' ' . $key . '="' . $val . '"';
	        $url .= ' />';
	    }
	    return $url;
	}
	static function mime($name){
		return getimagesize($name)['mime'];
	}
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
	
	
	/**
	 * 
	 * 判断一个图片是否是包含alpha通道的png
	 * @param string $file
	 * 方法来自 
	 http://www.welefen.com/how-to-detect-png-has-alpha-transparent.html
	 */
	static function is_alpha_png($file) {
	    if (! file_exists ( $file )) {
	        return false;
	    }
	    $f = @fopen ( $file, 'r' );
	    if (! $f) {
	        return false;
	    }
	    $bin = fread ( $f, 29 );
	    fclose ( $f );
	    $info = @unpack ( "C8c/C8char/C4width/C4height/Cdepth/Ccolortype", $bin );
	    $png = array (
	        137, 
	        80, 
	        78, 
	        71, 
	        13, 
	        10, 
	        26, 
	        10 
	    );
	    //判断头是否是png文件
	    for($i = 0; $i < 8; $i ++) {
	        if ($png [$i] != $info ['c' . ($i + 1)]) {
	            return false;
	        }
	    }
	    list ( $width, $height ) = getimagesize ( $file );
	    //这里用width3和4就可以了
	    $w = $info ['width3'] * 256 + $info ['width4'];
	    $h = $info ['height3'] * 256 + $info ['height4'];
	    //判断当前获取跟系统获取的值是否相同
	    if ($width != $w || $height != $h) {
	        return false;
	    }
	    $depth = $info ['depth'];
	    $colorType = $info ['colortype'];
	    if ($depth == 8 || $depth == 16) {
	        if ($colorType == 6) {
	            return true;
	        }
	    }
	    return false;
	}
	
	/*
	 * 判断图片是否为动态
	 */
	static function is_animated_gif($filename) {
		$fp = fopen($filename, 'rb');
		$filecontent = fread($fp, filesize($filename));
		fclose($fp);
		return strpos($filecontent, chr(0x21) . chr(0xff) . chr(0x0b) . 'NETSCAPE2.0') === FALSE ? 0 : 1;
	}
	
 
}