<?php
/**
  	
  	https://github.com/innerTeam/jQuery.AjaxFileUpload.js
  	
	@auth Kang Sun <68103403@qq.com>
 	@date 2014
	
*/
namespace Ken\Web\doc\module\content\field; 

class file extends base{  
	 public function render($value  = null){ 
	 	
	  	$str = $this->file($value);
	  	if($this->mi) $multiple = true;
 	 	return \Form::file($this->fid,[ 
 	 		'value'=>$value,
 	 		'rel'=>$this->name,
 	 		'multiple'=>$multiple,
 	 	]).$str;
 	 } 
 	 static function grid($value){  
 	 	 return static::resize_image($value);
 	 }
}