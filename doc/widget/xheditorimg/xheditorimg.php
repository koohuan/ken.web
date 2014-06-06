<?php
/**
	 
xheditor 上传图片
 
*/
namespace Ken\Web\doc\widget\xheditorimg;

class xheditorimg extends \Widget{ 
	public $id;
	// img 返回原图
	public $img = false;  
	public $name;
	function run(){  
		$id = str_replace('#','',$this->id)."_editor";
		$name = 'upload_'.$id;
		$img = $this->img?1:0;
 		\HTML::code("  
 			$('#".$name."').ajaxfileupload({
			      'action': '".url('content/file/upload')."',
			      'params': {
		        		name:'".$name."', 
		        		img:".$img.",
		        		mi:1
			      },
			      'onComplete':function(response) {  
			      	  	".$id.".focus();
			        	".$id.".pasteHTML(response);
			   	  } ,
			      'onStart': function() {
			       	  
			      },
			      'onCancel': function() {
			       	 
			      }
			});  
			
 		");  
 		\HTML::code("
 			.file {
			position: relative;
			display: inline-block;  
			overflow: hidden;
			color: #1E88C7;
			text-decoration: none;
			text-indent: 0; 
		}
		.file input {
			position: absolute;
			font-size: 100px;
			right: 0;
			top: 0;
			opacity: 0;
		}
		.file:hover {
			 
		}
 		");
 		return "<a href='javascript:;' class='file'><i class='fa fa-image'><input type='file' name='".$name."' id='".$name."' /></i></a>";
 	 
	}
}