<?php   

$this->layout('default'); 
?>
	
<?php echo $this->start('content');?>

<blockquote><?php echo __('Update User Password');?>
<small><?php echo $one->username;?> | <?php echo $one->email;?></small>	
</blockquote>
	
	
<?php
 
	widget('form',[
		 'error'=>$error,
		 'fields'=>$element,
		 'submit'=>$button?:'save',
	]);
?>	 
 
		
<?php echo $this->end();?>
