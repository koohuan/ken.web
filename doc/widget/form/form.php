<?php
/**
	自动化生成表单
  
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