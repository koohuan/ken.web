<?php
/**
	 
	
	 
*/
namespace Ken\Web\doc\widget\tip;

class tip extends \Widget{ 
	public $id;
	public $par = []; 
	function run(){ 
		$url = $this->publish(__DIR__.'/misc'); 
		$this->par = \Json::encode($this->par);
 	 
 		\HTML::code(" 
 			$('".$this->id."').qtip({$this->par});
 		"); 
 		\HTML::css($url.'jquery.qtip.min.css');
 		\HTML::js($url.'jquery.qtip.min.js'); 
 		
	}
}