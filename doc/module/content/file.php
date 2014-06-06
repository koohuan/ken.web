<?php
/**
 	content type
	@auth Kang Sun <68103403@qq.com>
 	@date 2014
	
*/
namespace Ken\Web\doc\module\content;  
 
class file extends \AdminController{ 
  	 
 	function init(){
 		parent::init(); 
 	 	
 	}
 	function uploadAction(){ 
 		 $name = $_POST['name'];
 		 $flag = $input = $_POST['input'];
 		 $mi = $_POST['mi'];
 		 $img = $_POST['img']?:false;
 		 if($_FILES[$name]['size']<1){
 		 	\Response::code(500);
 		 	exit;
 		 }
 	 
 		 $upload = new \Upload();
		 $rt = $upload->image($name); 
		 $d = $upload->get();
		 if($d){
		 	 /**
		 	  	[id] => 1
			    [name] => 537ae2b9d53c5.jpg
			    [url] => upload/2014/05/537ae2b9d53c5.jpg
			    [ext] => jpg
			    [mime] => image/jpeg
		 	 */
		 	 $op = [
 	 			'bgcolor' => '#f00', 
		        'quality' => 50,
		        'actions' =>[
		            'resize'=>[80, 60], 
		        ]
 	 		]; 
	 		 $u = \Image::set($d->url,$op);  
	 		 if($mi) $input = $input.'[]';
	 		 if($flag){
		 	 	$str = "<div class='ajax_file'><input type='hidden' value=".$d->id." name='".$input."'>";
		 	 	$str .= "<img src='".base_url().$u."' /></div>";
		 	 }else{
		 	 	 
		 	 	if(false === $img){
			 	 	$op = [
		 	 			'bgcolor' => '#f00', 
				        'quality' => 75,
				        'actions' =>[
				            'resize'=>[400, 300], 
				        ]
		 	 		]; 
		 		 	$u = \Image::set($d->url,$op);
			 	 	$str = "<a href='".base_url().$d->url."' target='_blank'><img src='".base_url().$u."' /></a>";
		 	 	}else{
		 	 		$str = "<img src='".base_url().$d->url."' />";
		 	 	}
		 	 }
		 	 
		 	 echo $str;		 	
		 }else{
		 	 echo false;
		 }
 	}
 	 
	 
}