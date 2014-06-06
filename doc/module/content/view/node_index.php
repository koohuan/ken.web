<?php 
use Ken\Web\doc\module\content; 
$this->layout('default'); 
HTML::code("
$('#aa').click(function(){
	$('#search').toggle();
});");
$slug = $post->slug;
$_action = content::hook_exists($slug); 
?>
	
<?php echo $this->start('content');?> 
<blockquote><?php echo $post->name;?> <small><?php echo $slug;?></small></blockquote>
<p>
	<?php if($is_search){?>
		<i class="fa-2x fa fa-search hand" id='aa'></i>
	<?php }?>
	<?php if($is_form){?>
		<a href="<?php echo url('content/node/save',['id'=>$_GET['id']]);?>" title="<?php echo __('Create');?>">	
			<i class="fa-2x fa fa-plus-square"></i>
		</a>
	<?php }?>
	<a href="<?php echo url('content/node/index',['id'=>$_GET['id']]);?>" title="<?php echo __('Reset');?>">	
		<i class="fa-2x fa fa-refresh" ></i>
	</a>
	<?php if($report){ unset($_GET['page'])?>
		<a href="<?php echo url('content/node/export',$_GET);?>" title="<?php echo __('Export Reporting');?>">	
			<i class="fa-2x fa fa-paperclip"></i>
		</a>
	<?php }?>
</p>
<?php if($is_search){?>
<div id='search' class='well' style='display:none'>	
	<?php echo \Form::open('form_search',['method'=>'GET']);?> 
		  <input  name="id"  type='hidden' value="<?php echo $_GET['id'];?>">	
		  <label><?php echo __('ID');?></label>
		  <input  name="_id" class='form-control' value="<?php echo $_GET['_id'];?>">	  
		  <?php echo $search; ?>
		  
		  <button style="margin-top: 20px;" type="submit" class="btn btn-primary">
		  	  <?php echo __('Search');?>
		  </button>	
		  <a href="<?php echo url('content/node/index',['id'=>$_GET['id']]);?>">
			  <button style="margin-top: 20px;" type="button" class="btn btn-primary">
			  	  <?php echo __('Reset');?>
			  </button>	
		  </a>
	<?php echo \Form::close();?> 	
</div>	
<?php }?>	
<?php if(!$posts) { goto end;}?>
 <table class="table table-striped table-bordered table-hover">
    <tbody><tr> 
     	<th><?php echo __("ID");?></th> 
    	<?php foreach($fs as $label=>$v){?>
       		<th><?php echo __($label);?></th> 
      	<?php }?>
        <?php if($_action || $is_form){?>
        <th style="width:200px">
        	<?php if($is_form){?>
		 		<a href="<?php echo url('content/node/save',['id'=>$_GET['id']]);?>" title="<?php echo __('Create');?>">	
					<i class="fa fa-plus-square"></i>
				</a>
			<?php }?>
     	</th>
     	<?php }?>
    </tr>
    <?php foreach($posts as $vo){?> 
    <tr>
    	<td><?php echo $vo->id;?></td> 
        <?php foreach($fs as $label=>$v){?>
       		<td><?php 
       			$val = content::hook($post->load?:$slug,$v,$vo->$v,$vo);
       			if(!$val)
       				$val = content::data($_GET['id'],$vo,$v);
       			echo $val;
       			?></td> 
      	<?php }?>
      	<?php if($_action || $is_form){?>
	        <td>     
	        		<?php echo content::hook($post->load?:$slug,'_action',$vo);?>
	    		<?php if($is_form){?>		
					<a href="<?php echo url('content/node/save',['id'=>$_GET['id'],'nid'=>$vo->id]);?>">		
						<i class="fa fa-edit"></i>
					</a> 
						
				<?php }?>
				<?php 
					$co = content::get_coloum_id($id);
				 
				?>
				<?php if($co['sort']){?>
					<a href="<?php echo url('content/node/up',['id'=>$id,'fid'=>$vo->id]);?>" title="<?php echo __('Up  1 step');?>">	
						<i class="fa fa-chevron-up"></i>
					</a>
					<a href="<?php echo url('content/node/down',['id'=>$id,'fid'=>$vo->id]);?>"  title="<?php echo __('Down 1 step');?>">
						<i class="fa fa-chevron-down"></i>	
					</a>
				<?php }?>	
				<?php if($co['top']){?>	
					<a href="<?php echo url('content/node/upp',['id'=>$id,'fid'=>$vo->id]);?>"  title="<?php echo __('Up to top');?>">
						<i class="fa fa-angle-double-up"></i>
					</a>
					<?php if($vo->top!=0){?>
						<a href="<?php echo url('content/node/close',['id'=>$id,'fid'=>$vo->id]);?>"  title="<?php echo __('Close Up to top');?>">	
					 		<i class="fa fa-refresh"></i>
					 	</a>	
				 	<?php }?>
				 <?php }?>
				 	 
				 
						<a    href="<?php echo url('content/node/delete',['id'=>$id,'fid'=>$vo->id]);?>" title="<?php echo __('Delete');?>" onclick="return confirm('<?php echo __('Remove item?');?>');">				
							<i class="fa fa-trash-o"></i>
						</a>
				 
								
			
	        </td>
        <?php }?>
    </tr>
    <?php }?>
</tbody>
</table>
<?php echo $paginate;
end:	
?>
				
<?php echo $this->end();?>
