<?php  
$this->layout('default'); 
$only = $_GET['id']?true:false;

?>
	
<?php echo $this->start('content');?>
 
 
 <p class="bg-<?php echo $_GET['fid']?'warning':'info';?> "><?php echo $_GET['fid']?__('Update'):__('Create');?> <?php echo __('Content Type');?></p> 
	<form class='well' method='POST'>
	  
	  <div class="form-group">
	    <label><?php echo __('Name');?></label>
	    <?php echo Form::input('name',['class'=>'form-control','value'=>$one->name]);?> 
	  </div>
	  <div class="form-group">
	    <label><?php echo __('Mysql Table');?></label>
	    <?php echo Form::input('slug',['class'=>'form-control' ,'readonly'=>$only,'value'=>$one->slug ]);?> 
	  </div>
	  <div class="form-group">
	    <label><?php echo __('Autoload');?> \classes\content </label>
	    <?php echo Form::input('load',['class'=>'form-control'  ,'value'=>$one->load ]);?> 
	  </div>
	  <div class="form-group">
	    <label><?php echo __('Memo');?></label>
	    <?php echo Form::input('memo',['class'=>'form-control','value'=>$one->memo]);?> 
	  </div>
	  	  
	   
	  		 
	  <button type="submit" class="btn btn-primary"><?php echo __('Save');?></button>
	</form>
 
		
<?php echo $this->end();?>
