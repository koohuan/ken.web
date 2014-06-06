<?php
/**
  	
	@auth Kang Sun <68103403@qq.com>
 	@date 2014
	
*/
namespace Ken\Web\doc\module\content\field; 

class relation extends base{ 
 	 public function render($value = null){
 	 	$t = $this->type;
 	 	$m = $this->f->is_m_value?true:false;
 	 	$multiple  = false;
 	 	if($this->mi){ 
 	 		$option[""] = __("Please Select");
 	 		$option = $option + $this->value;
 	 		$this->name = $this->name.'[]';
 	 		$multiple = true;
 	 		//关联字段取中间表数据
 	 		$a = $value['a'];
 	 		$all = \DB::w()->from($value['table'])->where($value['b'].'=?',[$value['nid']])
 	 				->all();
 	 		if($all){
 	 			unset($value);
 	 			foreach($all as $one){
 	 				$value[$one->$a] = $one->$a;
 	 			}
 	 		} 
 	 	}else{
 	 		$arr = explode('.',$this->slug);
	 	 	$n = $arr[2];
	 	 	$all = \DB::w()->table($arr[1])->order_by('id desc')->all();
	 	 	$option = [];
	 	 	$option[""] = __("Please Select");
	 	 	if($all){
	 	 		foreach($all as $v){
	 	 			$option[$v->id] = $v->$n;
	 	 		}
	 	 	}
 	 	}
 	 	 
 	 	return \Form::select($this->name,[
 	 		'class'=>'form-control',
 	 		'value'=>$value,
 	 		'multiple'=>$multiple,
 	 		'option'=>$option
 	 	]);
 	 }
 	 
 	 static function grid($value){  
 	 	  if(is_array($value))
 	 	  	  $str = implode(' ',$value);
 	 	  else
 	 	  	  $str = $value;
 	 	  return $str;
 	 }
	 
}