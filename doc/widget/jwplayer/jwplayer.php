<?php
/**
	 
*/
namespace Ken\Web\doc\widget\jwplayer; 

class jwplayer extends \Widget{ 
	public $id;
 	public $par = [];  
  
	function run(){ 
		$url = $this->publish(__DIR__.'/misc');    
 	 	$this->par['flashplayer'] = $url."/jwplayer.flash.swf";
	 	$this->par['width'] = $this->par['width']?:400;
	 	$this->par['height'] = $this->par['height']?:300; 
	 	$this->par = \Json::encode($this->par);
 		\HTML::code(" 
 			var jw = jwplayer('".$this->id."').setup({$this->par}); 
 		");  
 		\HTML::js($url.'jwplayer.js'); 
 		\HTML::js($url.'jwplayer.html5.js'); 
 		
	}
}