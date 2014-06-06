<?php
/**
	 
http://ios-checkboxes.awardwinningfjords.com/
 
*/
namespace Ken\Web\doc\widget\checkbox;

class checkbox extends \Widget{ 
	public $id = ':checkbox';
	public $par = [
		'checkedLabel'=>'YES',
  		'uncheckedLabel'=> 'NO'
	]; 
 	function run(){ 
		$url = $this->publish(__DIR__.'/misc'); 
		$this->par = \Json::encode($this->par);
 	 
 		\HTML::code(" 
 			$('".$this->id."').iphoneStyle({$this->par});
 		"); 
 		\HTML::css($url.'style.css');
 		\HTML::js($url.'iphone-style-checkboxes.js'); 
 		
	}
}