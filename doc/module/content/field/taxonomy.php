<?php
/**
  	
	@auth Kang Sun <68103403@qq.com>
 	@date 2014
	
*/
namespace Ken\Web\doc\module\content\field;  

class taxonomy extends base{ 
	 static $data;
	 static $span = 1;
 	 function leep($table , $name , $pid = 0){
 	 	$rs = \DB::w()->table($table)->where('pid=?',[$pid])->order_by('id desc')->all();
  	 	$sp = null; 
 	 	if(static::$span > 1) { 
 	 		for($i = 0;$i<static::$span;$i++){
 	 			$sp .="&nbsp;&nbsp;";
 	 		} 
 	 	} 
 	 	
 	 	foreach($rs as $r){  
 	 		static::$data[$r->id] = $sp.$r->$name;  
 	 		if( $one = \DB::w()->table($table)->where('pid=?',[$r->id])->one() ){ 
 	 			static::$span++;
 	 			$this->leep($table,$name,$r->id,static::$span);
 	 		} 
 	 	} 
 	  	
 	 	return static::$data;
 	 }
	 public function render($value = null){
	 	static::$data = null;
	 	static::$data[""] = __("Please Select");  
 	 	$t = $this->type;
 	 	$m = $this->f->is_m_value?true:false;
 	 	$option[""] = __("Please Select");  
 	 	$arr = explode('.',$this->slug);
 	 	$n = $arr[2]; 
 	 	if($n){
	 	 	$option = $this->leep($arr[1] ,$n); 
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