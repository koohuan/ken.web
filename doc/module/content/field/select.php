<?php
/**
  	
	@auth Kang Sun <68103403@qq.com>
 	@date 2014
	
*/
namespace Ken\Web\doc\module\content\field; 

class select extends base{ 
 	 
	 public function render($value = null){
 	 	$t = $this->type;
 	 	$m = $this->f->is_m_value?true:false;
 	 	$option[""] = __("Please Select");  
 	 	$arr = explode('.',$this->slug);
 	 	$n = $arr[2]; 
 	 	if($n){
	 	 	$all = \DB::w()->table($arr[1])->order_by('id desc')->all();
	 	 	 
	 	 	if($all){
	 	 		foreach($all as $v){
	 	 			$option[$v->id] = $v->$n;
	 	 		}
	 	 	} 
 	 	}else{
 	 		if($this->f->values){
 	 			$option[""] = __("Please Select");  
	 	 		$list = unserialize($this->f->values);
	 	 		foreach($list as $k=>$v){
	 	 			$option[$k] = __($v);
	 	 		}
	 	 		
	 	 	}
 	 	} 
 	 	return \Form::select($this->name,[
 	 		'class'=>'form-control',
 	 		'value'=>$value,
 	 		'option'=>$option
 	 	]);
 	 }
}