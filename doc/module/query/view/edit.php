<?php $this->layout('default');?>
	
<?php $this->start('content');?>
<blockquote>
	<?php echo $_GET['id']?__('Edit'):__('Add');?>  
</blockquote>
	
	<?php echo Form::open();?>
	<div class="form-group">
	    <label><?php echo __('Slug');?></label>
	    <input name='slug' value="<?php echo $post->slug;?>" >
	</div>	
	<div class="form-group">
	    <label><?php echo __('Memo');?></label>
	    <input name='memo' value="<?php echo $post->memo;?>" >
	</div>	
	
	<div class="form-group">
	    <label><?php echo __('SQL');?></label>
	    <textarea name='sql' style="width:500px;height:200px;"><?php echo $post->sql;?></textarea>
	</div>		
	
	<div class="form-group">
		<input type='submit' class='btn btn-primary' value="<?php echo __('Save');?>"> 
	</div>		
	<?php echo Form::close();?>
<?php $this->end();?>