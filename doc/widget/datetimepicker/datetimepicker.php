<?php
/**
 
*/
namespace widget\datetimepicker;  
class Ken\Web\doc\datetimepicker extends \Widget{ 
	public $id;
	function run(){ 
		$url = $this->publish(__DIR__.'/misc'); 
		$this->par = \Json::encode($this->par);
 	  	\HTML::css("//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css");
 	  	\HTML::js("//code.jquery.com/ui/1.10.4/jquery-ui.js");
 		\HTML::css($url.'jquery.datetimepicker.css');
 		\HTML::js($url.'jquery.datetimepicker.js'); 
 		\HTML::code("
 			$( '".$this->id."' ).datetimepicker();
 		");
 		
	}
}