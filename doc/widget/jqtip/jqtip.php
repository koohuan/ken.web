<?php
/**
  http://qtip2.com/download
*/
namespace Ken\Web\doc\widget\jqtip; 
 
class jqtip extends \Widget{ 
	public $id = '.tip';
	public $par = []; 
	public $ajax = false;
	function run(){ 
		$url = $this->publish(__DIR__.'/misc'); 
		   
		if($this->ajax !== false){ 
			$this->par['content']=[
                	"text"=>"js:function(event, api){
                		$.ajax({
							url: api.elements.target.attr('rel')
						})
						.then(function(content) {
							api.set('content.text', content);
						}, function(xhr, status, error) {
						});
							return 'Loading...';
						 
						}",
						"position"=>[
							'my'=>'top left',
							'at'=>"bottom right"
						],
						"style"=>'qtip-wiki',
	            ];
		}
		
		$this->par = \Json::encode($this->par);
		
 		\HTML::code(" 
 			$('".$this->id."').qtip({$this->par});
 		"); 
 		\HTML::css($url.'jquery.qtip.min.css');
 		\HTML::js($url.'jquery.qtip.min.js'); 
 		\HTML::js($url.'imagesloaded.pkg.min.js'); 
	}
}