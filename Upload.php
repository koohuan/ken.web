<?php 
/**
    上传
  	
  	$upload = new Upload();
	$upload->image('foo');
	成功写入 返回true,
	已存在  返回 false
	错误    返回  错误信息
	
    $upload->get(); 取得数据库信息。
		
  	CREATE TABLE `files` (
	  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  `name` varchar(200) NOT NULL,
	  `url` varchar(255) NOT NULL,
	  `ext` varchar(50) NOT NULL,
	  `mime` varchar(100) NOT NULL,
	  `size` int NOT NULL,
	  `md5` varchar(32) NOT NULL DEFAULT '',
	  `memo` varchar(200) NOT NULL
	) COMMENT='' ENGINE='MyISAM' COLLATE 'utf8_general_ci'; 

 	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
 */
namespace Ken\Web;
class Upload
{ 
	public $storage;
	public $file;
	public $url;
	public $get;
 	function image($name){
 		$this->file = new \Upload\File($name, $this->storage);
 		$this->todo();
 	} 
	function __construct( ){ 
		$this->url = 'upload/'.date('Y').'/'.date('m');
		$dir = public_path().'/'.$this->url;
		if(!is_dir($dir)) mkdir($dir,0775,true);
  		$this->storage = new \Upload\Storage\FileSystem($dir); 
		return $this;
  	}
  	function todo( $type = ['image/png', 'image/gif','image/jpg','image/jpeg'],$size= "5M"){
  		$new_filename = uniqid();
		$this->file->setName($new_filename); 
  		// Validate file upload
		// MimeType List => http://www.webmaster-toolkit.com/mime-types.shtml
		$this->file->addValidations([
		    new \Upload\Validation\Mimetype( $type ), 
		    // Ensure file is no larger than 5M (use "B", "K", M", or "G")
		    new \Upload\Validation\Size($size)
		]); 
 		$data = array(
 			'name'		=> $this->file->getNameWithExtension(), 
		    'url'       => $this->url."/".$this->file->getNameWithExtension(), 
		    'extension'  => $this->file->getExtension(),
		    'mime'       => $this->file->getMimetype(),
		    'size'       => $this->file->getSize(),
		    'md5'        => $this->file->getMd5(),
		    'dimensions' => $this->file->getDimensions()
		); 
		try {
			$one = DB::w()->from('files')->where('md5=?',[$data['md5']])->one();   
		    if(!$one){ 
		    	$this->file->upload();
		    	$insert = [
		    		'name'=>$data['name'],
		    		'url'=>$data['url'],
		    		'ext'=>$data['extension'],
		    		'mime'=>$data['mime'],
		    		'size'=>$data['size'],
		    		'md5'=>$data['md5'],
		    		'memo'=>serialize($data['dimensions']),
		    	];
		    	$id = DB::w()->insert('files',$insert);
		    	$this->get  = (object)$insert;
		    	$this->get->id = $id;
		    	return true;
		    }
		    $this->get  = $one;
		    return false;
		} catch (\Exception $e) {
		    $errors = $this->file->getErrors();
		    return $errors[0];
		}
  	}
  	
  	function get(){
  		return $this->get;
  	}
	
 
}