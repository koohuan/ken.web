视图
========
`app`这个namespace下可直接使用 `$this->view(name,[])`

layout 视图使用
以theme为例 `webroot/themes/admin/default.layout.php`


	<?php  echo $this->extend_layout('left_menu');?>

输出内容

	<?php echo $this->view['content'];?> 

index.php 视图说明 
	
	<?php $this->layout('default'); ?>
	
	//对应 echo $this->view['content'] 内容
	<?php echo $this->start('content');?>
		...
	<?php echo $this->end();?>

设置字段`username` 不为空，且值在3到5之间

	Validate::set('username',[
			['not_empty'],
			['between',3,5]
	]);

完整事例

	Validate::set('username',[
		['not_empty','message'=>'not empty'], 
	]);
	Validate::set('password',[
		['not_empty','message'=>'not empty'], 
	]); 
	//验证成功
	if(Validate::run()){  
		
	}
	//取得验证错误信息
	$vali = Validate::message();
 	if($vali) $error  = $vali[0];
	//显示视图
 	$this->view('login',['error'=>$error]);
	