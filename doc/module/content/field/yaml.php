<?php
/**
  	YAML��ʽ
	@auth Kang Sun <68103403@qq.com>
 	@date 2014
	
*/
namespace Ken\Web\doc\module\content\field; 

class yaml extends base{ 
 	 
	 public function render($value  = null){
	 	if($value)
 	 	 	$value = \third\Spyc::YAMLDump(unserialize($value));
 	 	return \Form::text($this->name,[
 	 		'class'=>'form-control',
 	 		'style'=>'height:200px',
 	 		'value'=>$value,
 	 	]);
 	 }
 	 //��������ʱ�ı����ݵ�ֵ
 	 static function save($value){
 	 	return \third\Spyc::YAMLLoadString($value); 
 	 }
}