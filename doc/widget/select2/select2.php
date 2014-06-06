<?php
/**
 
*/
namespace Ken\Web\doc\widget\select2; 

class select2 extends \Widget{ 
	public $id = 'select';
	public $par = []; 
	function run(){ 
		$url = $this->publish(__DIR__.'/misc'); 
		$this->par = \Json::encode($this->par);
 		\HTML::code(" 
 			$('".$this->id."').select2({$this->par});
 		"); 
 		\HTML::css($url.'select2.css');
 		\HTML::js($url.'select2.min.js'); 
 		
	}
}