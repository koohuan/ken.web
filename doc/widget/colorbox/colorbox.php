<?php
/**
	 
http://www.jacklmoore.com/colorbox/example4/	
 
*/
namespace Ken\Web\doc\widget\colorbox;

class colorbox extends \Widget{ 
	public $id;
	public $par = []; 
	public $theme = 4;
	function run(){ 
		$url = $this->publish(__DIR__.'/misc'); 
		$this->par = \Json::encode($this->par);
 	 
 		\HTML::code(" 
 			$('".$this->id."').colorbox({$this->par});
 		"); 
 		\HTML::css($url.'example'.$this->theme.'/colorbox.css');
 		\HTML::js($url.'jquery.colorbox-min.js'); 
 		
	}
}