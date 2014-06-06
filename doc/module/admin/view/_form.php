 

<blockquote><?php echo $title;?></blockquote>
 
<?php
 
	widget('form',[
		 'error'=>$error,
		 'fields'=>$element,
		 'submit'=>$button?:'save',
	]);
?>	 
 
 
<?php HTML::output();?>