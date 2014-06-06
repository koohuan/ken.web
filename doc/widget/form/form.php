<?php
/**
	自动化生成表单
	F::get('widget')->push('widget\form\form',[
		 'error'=>$error,
		 'fields'=>[
		 	'username'=>[
		 		'label'=>'username',
		 		'element'=>'input',
		 	],
		 	
		 
		 ],
		 'submit'=>'submit',
	]);
	
	注册widget
	
	F::set('widget',function(){ 
		 return new Widget(__DIR__.'/assets' ,base_url().'assets');
	});	
*/
namespace Ken\Web\doc\widget\form;
 
class form extends \Widget{ 
 	public $error;
	public $fields = []; // 参数 option 是 select html option[]
	public $submit = 'submit'; 
	function run(){  
		$this->view('form',[
			'error'=>$this->error,
			'fields'=>$this->fields,
			'submit'=>$this->submit, 
		]);
 		
	}
}