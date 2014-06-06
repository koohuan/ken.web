<?php
/**
  	
	@auth Kang Sun <68103403@qq.com>
 	@date 2014 
*/
namespace Ken\Web\doc\module\content\field;  
use third\Spyc;
class base{ 
	 public $type;
	 public $name;
	 public $slug;
	 static $save;
	 static $r;
	 public $f;
	 public $fid;
	 public $mi = 0;//多值
	 static $relation;
	 public $value;
	 static function relation($f){
	 	//判断是关联表
 	 	unset($rel);
 	 	if(in_array($f->field,['relation','image','file']) && $f->is_m_value){
 	 		$rt = explode('.',$f->slug);
 	 		if($rt[2]){
 	 			$rel['a'] = $rt[0];
 	 			$rel['table'] = $rt[1];
 	 			$rel['b'] = $rt[2];
 	 			$rel['relation'] = true;
 	 		}else{
 	 			$rel['a'] = 'fid';
 	 			$rel['table'] = $rt[0];
 	 			$rel['b'] = $rt[1]; 
 	 			$rel['relation'] = true;
 	 		}
 	 	}
 	 	return $rel;
	 }
 	 public function __construct($table,$f){
 	 	$this->f = $f; 
 	 	$this->type = $f->field;
 	 	$this->slug = $slug = $f->slug;  
 	 	$this->name = "name".$f->id; 
 	 	$arr = explode('.',$slug);  
 	 	$rel = static::relation($f); 
 	 	static::$save[$table][$arr[0]] = [
 	 		'name'=>$this->name, 
 	 		'field'=>$f->field,
 	 		'relation'=>$rel,
 	 		'_widget' => unserialize($f->widget),
 	 		'_validate' => unserialize($f->validate),
 	 	];
 	 	if($rel){
 	 		if($f->is_m_value)
 	 			$this->mi = true; 
 	 		$this->value = unserialize($f->values);
 	 		static::$relation[] = [
 	 			'name'=>$this->name,  
 	 			'table'=>$rel['table'],
 	 			'a'=>$rel['a'],
 	 			'b'=>$rel['b'],
 	 			'relation' => true
 	 		];
 	 		
 	 	}
 	 	static::$r[$this->name] = $arr[0];
 	 }
 	 
 	 public function render($value = null){
 	 	 
 	 	$t = $this->type;
 	 	return \Form::$t($this->name,[
 	 		'class'=>'form-control',
 	 		'value'=>$value,
 	 	]);
 	 }
 	 
 	 static function resize_image($value){ 
 	 	 	if(!is_array($value)) {
 	 	 		$in[] = $value;
 	 	 	}else{
 	 	 		$in = $value;
 	 	 	}    
 	 	 	$all = \DB::w()->from('files')->where('id in ('.\DB::in($in).')',$in); 
 	 	 	if(is_array($in) && implode('',$in) )
 	 	 		$all = $all->order_by("FIELD ( id ,".implode(',' , $in).") ");
 	 	 	$all = $all->cache()->all();   
 	 	 	
 	 	 	if(!$all) return;
 	 	 	foreach($all as $one){ 
 	 	 		$str[]= static::resize_image_one($one);
 	 	 	}
 	 	 	if(count($str)>1){
 	 	 		$str = \Arr::get($str,2);
 	 	 		$str = implode(' ',$str)."<i title='".__('More...')."' class='fa fa-caret-square-o-right fa-3x' style='float:right;padding-top:5px;'></i>";
 	 	 	}else{
 	 	 		$str = implode(' ',$str);
 	 	 	}
 	 	 	
 	 	 	return $str; 
 	 }
 	 
 	 static function resize_image_one($one , $name = null){
 	 		$url = $one->url;
 	 		$op = [
 	 			'bgcolor' => '#f00', 
		        'quality' => 50,
		        'actions' =>[
		            'resize'=>[80, 60], 
		        ]
 	 		]; 
 	 		$u = \Image::set($url,$op); 
 	 		unset($output); 
 	 		if($name){
 	 			$output = "<div class='ajax_file'><input type='hidden' value=".$one->id." name='".$name."'>";
 	 		}
		 	$output .= "<img src='".base_url().$u."' />";
		 	if($name){
		 		$output .= "<i class='fa fa-trash-o fremove'></i></div>";
		 	}
 	 		return $output;
 	 }
 	 function file($value){  
 	 	if(strpos($this->slug,'.')!==false){
 	 		$this->mi = true;
 	 	}
 	 	if($value){ 
 	 		$name = $this->name;
 	 		if(true === $this->mi) {
 	 			$name = $name."[]"; 
 	 		}   
 	 		if(is_array($value) && $value['nid']){
 	 			$one = \DB::w()->from($value['table'])->where($value['b'].'=?',[$value['nid']])->order_by("id asc")->all();
 	 			if($one){
 	 				$a = $value['a'];
 	 				unset($in);
 	 				foreach($one as $list){
 	 					$in[] = $list->$a;
 	 				}  
 	 				$all = \DB::w()->from('files')->where('id in ('.\DB::in($in).')',$in)->order_by("FIELD ( id ,".implode(',' , $in).") ")->cache()->all(); 
 	 				if($all){
 	 					unset($one);
 	 					$output = "<div class='handle'>";
 	 					foreach($all as $one){
 	 						$output .= static::resize_image_one($one,$name);
 	 					}
 	 					$output .="</div>";
 	 				}
 	 			}
 	 		}else{ 
 	 			$one = \DB::w()->from('files')->where('id=?',[$value])->one();
	 	 		if(!$one) goto Next; 
			 	$output = static::resize_image_one($one , $name);
 	 		}
 	 		
 	 	}
 	 	Next:
 	 	
 	 	$this->fid = $id = "file_".$this->name."_AJAX_".\Str::uid();
 	 	if($this->mi){
 	 		$h = "function(response) {
			       	$('#".$this->name."').append(response); 
			   }";
		}else{
			$h = "function(response) {
			       	$('#".$this->name."').html(response); 
			   }";
		}
	 	\HTML::code(" 
	 		
	 		$('#".$id."').ajaxfileupload({
			      'action': '".url('content/file/upload')."',
			      'params': {
		        		name:'".$id."',
		        		input:'".$this->name."',
		        		mi:".$this->mi."
			      },
			      'onComplete':".$h." ,
			      'onStart': function() {
			       	  
			      },
			      'onCancel': function() {
			       	 
			      }
			});  
	 	");
	 	return "<div id='".$this->name."'>".$output."</div>";
 	 }
 	 
}