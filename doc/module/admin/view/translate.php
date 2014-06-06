<?php  
$this->layout('default'); 
HTML::code("
	$('#select').change(function(){ 
		var u = $(this).val(); 
		if(u)
			window.location.href=u;
	});
");
?>
	
<?php echo $this->start('content');?>
<h2><?php echo __('Select Language');?></h2>
<p>
<select id="select" style="width:150px;">
	<option  value="">
  			<?php echo __('please select');?>
  		</option>
  <?php foreach($vs as $v){?>
    	
   		<option 
   			<?php if($url == url('admin/translate/index',['id'=>$v]) ){?> selected <?php }?>
   			value="<?php echo url('admin/translate/index',['id'=>$v]);?>"
   		>
  			<?php echo substr($v,1);?>
  		</option>
   
  <?php }?>
</select>
</p>	
<?php echo \Form::open();?>	
	
	<textarea name='txt' style='width:100%;height:300px;'><?php echo $post;?></textarea>
	
	<input type='submit' class='btn btn-primary' value="<?php echo __('Save');?>"> 
<?php \Form::colse();?>


	
<?php echo $this->end();?>
