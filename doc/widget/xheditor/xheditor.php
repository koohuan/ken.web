<?php
/**
	 
http://xheditor.com/
 
*/
namespace Ken\Web\doc\widget\xheditor;

class xheditor extends \Widget{ 
	public $id;
	public $par = [];  
	function run(){ 
		$url = $this->publish(__DIR__.'/misc'); 
		$this->par = \Json::encode($this->par);
		$id = str_replace('#','',$this->id);
 		\HTML::code(" 
 			var ".$id."_editor = $('".$this->id."').xheditor({$this->par});
 		");  
 		\HTML::js($url.'xheditor-1.1.14-zh-cn.min.js'); 
 		
	}
}