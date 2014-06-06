<?php  
$this->layout('default'); 
\HTML::code("
	.g_left{ width: 110px; float: left; } 
");
?>
	
<?php echo $this->start('content');?>
<div class="row">
  <div class="col-md-12">
    	
    	 <blockquote><?php echo $post->name;?> <small><?php echo $post->slug;?></small></blockquote>
		<p>
			<a href="<?php echo url('content/type/set',['id'=>$id]);?>#set"><i class="fa-2x fa fa-plus-square"></i></a> 
	 	</p>
	 			 
		 <table class="table table-hover">
		    <thead>
			<tr> 
		        <th><?php echo __('Label');?></th> 
				<th><?php echo __('Form Element');?></th>
		     	<th><?php echo __('Slug');?></th>  
		     	<th><?php echo __('Field');?></th> 
		        <th style="width:200px">
					<a href="<?php echo url('content/type/set',['id'=>$id]);?>#set" title="<?php echo __('Create');?>">	
						<i class="fa fa-plus-square"></i>
					</a>
				</th>
		    </tr>
		    </thead>
		    <tbody>
		    <?php if($all){foreach($all as $v){?> 
		    <tr <?php if($_GET['fid']==$v->id){?> class='bg-info' <?php }?> >
		        
		        <td><?php echo $v->label;?> | <?php echo $v->sort;?></td>
		        <td>name<?php echo $v->id;?></td> 
		        <td><?php echo $v->slug;?></td> 
		        <td><?php echo $v->field;?></td>
		        <td>    
		    		<a href="<?php echo url('content/type/set',['id'=>$id,'fid'=>$v->id]);?>#set" title="<?php echo __('Modify');?>">		
		    			<i class="fa fa-edit"></i>
		    		</a>
		    		
					
					
					<a href="<?php echo url('content/type/up',['id'=>$id,'fid'=>$v->id]);?>" title="<?php echo __('Up  1 step');?>">	
						<i class="fa fa-chevron-up"></i>
					</a>
					<a href="<?php echo url('content/type/down',['id'=>$id,'fid'=>$v->id]);?>"  title="<?php echo __('Down 1 step');?>">
						<i class="fa fa-chevron-down"></i>	
					</a>
					
					<a href="<?php echo url('content/type/upp',['id'=>$id,'fid'=>$v->id]);?>"  title="<?php echo __('Up to top');?>">
						<i class="fa fa-angle-double-up"></i>
					</a>
					<?php if($v->top!=0){?>
						<a href="<?php echo url('content/type/close',['id'=>$id,'fid'=>$v->id]);?>"  title="<?php echo __('Close Up to top');?>">	
					 		<i class="fa fa-refresh"></i>
					 	</a>	
				 	<?php }?>
					<span style="float: right;width:20px;height: 20px;" class='delete'>	
						<a  style="display: none;" href="<?php echo url('content/type/delete',['id'=>$id,'fid'=>$v->id]);?>" title="<?php echo __('Delete');?>" onclick="return confirm('<?php echo __('Remove item?');?>');">				
							<i class="fa fa-trash-o"></i>
						</a>
					</span>
								
		        </td>
		        
		    </tr>
		    <?php }}?>
		</tbody>
		</table>
 </div>
<div class="col-md-12">
	<a name="set"></a>
	<blockquote><?php echo __('Field Set');?> <small><?php echo __('Here is save field');?></small></blockquote>
	<p class="bg-<?php echo $_GET['fid']?'warning':'info';?> "><?php echo $_GET['fid']?__('Update'):__('Create');?></p> 
	<form class='well' method='POST'>
	  
	  <div class="form-group">
	    <label><?php echo __('Label');?></label>
	    <?php echo Form::input('label',['class'=>'form-control','value'=>$one->label]);?> 
	  </div>
	  <div class="form-group">
	    <label><?php echo __('Slug');?></label>
	    <?php echo Form::input('slug',['class'=>'form-control' ,'value'=>$one->slug,
	      'placeholder'=>'relation like: site_id.sites.name ',
	       ]);?> 
	  </div>
	  <div class="form-group">
	    <label><?php echo __('Field');?></label>
	    <?php echo Form::select('field',['option'=>$field,'class'=>'form-control','value'=>$one->field]);?> 
	  </div>
	  
	  
	  <div class="form-group">
	    <label><a href="<?php echo url('content/type/page',['name'=>'_validate']);?>" class='ajax cboxElement  colorbox'><?php echo __('Validate');?></a></label>
	    <?php echo Form::text('validate',['class'=>'form-control','value'=>$one->validate]);?> 
	    
	  </div>
	  
	  <div class="form-group">
	    <label><?php echo __('Widget');?></label>
	    <?php echo Form::text('widget',['class'=>'form-control','value'=>$one->widget]);?> 
	  </div>
	  	  	  		
	  <div class="form-group">
	    <label><?php echo __('Default Value');?></label>
	    <?php echo Form::text('values',['class'=>'form-control','value'=>$one->values]);?> 
	  </div>
	  
	  	  
	  <div class="form-group g_left" >
	    <label><?php echo __('Is Multiple Value');?></label>
	    <?php 
	      $checked = $one->is_m_value?true:false;
	      echo Form::checkbox('is_m_value',['value'=>1,'checked'=>$checked ]);?> 
	  </div>
	  	  
	  	  
	  <div class="form-group g_left">
	    <label><?php echo __('Show In Form');?></label>
	    <?php 
	      $checked = $one->is_form?true:false;
	      echo Form::checkbox('is_form',['value'=>1,'checked'=>$checked]);
	      ?> 
	  </div>
	  <div class="form-group g_left">
	    <label><?php echo __('Show In List');?></label>
	    <?php 
	    $checked = $one->is_index?true:false;	  
	    echo Form::checkbox('is_index',['value'=>1,'checked'=>$checked]);
	    ?> 
	  </div>
	  <div class="form-group g_left">
	    <label><?php echo __('Show In Search Form');?></label>
	    <?php 
	    $checked = $one->is_search?true:false;
	    echo Form::checkbox('is_search',['value'=>1,'checked'=>$checked]);
	    ?> 
	  </div>
	  <div class="form-group g_left">
	    <label><?php echo __('Export report');?></label>
	    <?php 
	    $checked = $one->is_report?true:false;
	    echo Form::checkbox('is_report',['value'=>1,'checked'=>$checked]);
	    ?> 
	  </div>	
	  	
	  <div style='clear:both;'></div>		 
	  <button  type="submit" class="btn btn-primary"><?php echo __('Save');?></button>
	</form>

</div>	
	
 
    
<?php echo $this->end();?>
