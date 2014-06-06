<?php   

$this->layout('default'); 
?>
	
<?php echo $this->start('content');?>

<blockquote><?php echo $title;?></blockquote>
<?php
 
	widget('form',[
		 'error'=>$error,
		 'fields'=>$element,
		 'submit'=>$button?:'save',
	]);
?>	 
 
		
<?php echo $this->end();?>
