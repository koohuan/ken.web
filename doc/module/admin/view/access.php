<?php   

$this->layout('default'); 
$g = [''=>__('Please Select')];
if($group){ 
	foreach($group as $v){
		$g[$v->id] = $v->name;
	}
	 
}
?>
	
<?php echo $this->start('content');?>

<blockquote><?php echo __('Access');?>
	<small>
		<?php echo __('Group Name');?>:<?php echo $post->name;?>
	</small>
</blockquote>

<div id='access'>
<?php echo Form::open();?>
	  <p><input id='select' type='checkbox' title="<?php echo __('Select All');?>"></p>
	  <?php $cu = ['r'=>'<i class="fa fa-wrench" title="'.__('Read').'"></i>','c'=>'<i class="fa fa-plus-square-o"  title="'.__('Create').'"></i>','u'=>'<i class="fa fa-pencil" title="'.__('Update').'"></i>',
	'd'=>'<i class="fa fa-lock" title="'.__('Delete').'"></i>'];
	if($data){ ?>
	  	 <label class='label label-success'><?php echo __('Custom Content');?></label>
	  	 	 
	  	 	<div class="row">
				<div class="col-md-4"> </div>
					
				<?php foreach($cu as $_k=>$_v){?>
					<div class="col-md-2 "> 
						<?php echo $_v;?> 
					</div>	
				<?php }?>
					
			</div>
			<div class='line'></div>
				
  	  	  <?php foreach($data as $k=>$v){?>
  	  	  	<div class="row">
			<div class="col-md-4"><label class='label label-default'><?php echo $v;?></label></div>
				
			<?php foreach($cu as $_k=>$_v){?>
				<div class="col-md-2 "> 
				 	 <span  <?php if($ac && in_array($k.'.'.$_k,$ac)){?>class="label-success"<?php }?>>
				 		<input type='checkbox' name="c[]"  value="<?php echo $k.'.'.$_k;?>" <?php if($ac && in_array($k.'.'.$_k,$ac)){?> checked="checked"<?php }?> > 
				 	 </span>
				</div>	
			<?php }?>
			</div>
			<div class='line'></div>
		<?php }?> 
 
	
	  <?php }?>
	
		
		<?php if($actions){?>
		<label class='label label-success'><?php echo __('Module');?></label>
	 	<div class="row">
		<?php $i=0; foreach($actions as $k=>$v){?>
				<?php if($i%4==0){ ?>
					
					<div class='line'></div>
				<?php }?> 
				<div class="col-md-2"><?php echo $v['txt'];?>
					<span  <?php if($ac && in_array($k,$ac)){?>class="label-success"<?php }?>>
						<input type='checkbox' name="c[]" value="<?php echo $k;?>" <?php if($ac && in_array($k,$ac)){?>checked="checked"<?php }?> >
					</span>	
					</div>
				 
				
				
		<?php $i++;}?>
		</div>
	 
	
	  <?php }?>
	
		
	<p style="margin-top:20px;">
		<input type="submit" value="<?php echo __('Save');?>" class="fa-2x">
	</p>
<?php echo Form::close();?>
</div>		
<?php echo $this->end();
HTML::code("
	#access .row:hover{background:#eee;}
");
HTML::code("
$('#select').change(function(){  
	if($(this).attr('checked')){ 
		$('#access').find('input').attr('checked','checked');
	}else{
		$('#access').find('input').removeAttr('checked');
	}
}); 
");
 ?>
