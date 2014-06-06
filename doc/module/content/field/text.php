<?php
/**
  	
	@auth Kang Sun <68103403@qq.com>
 	@date 2014
	
*/
namespace Ken\Web\doc\module\content\field; 

class text extends base{ 
 	 
	 public function render($value  = null){
 	 	$t = $this->type;
 	 	return \Form::$t($this->name,[
 	 		'class'=>'form-control',
 	 		'style'=>'height:200px',
 	 		'value'=>$value,
 	 	]);
 	 }
}