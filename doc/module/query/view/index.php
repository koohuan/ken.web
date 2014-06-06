<?php $this->layout('default');?>
	
<?php $this->start('content');?>
<blockquote><?php echo $post->memo?:__('Query Builder');?> 
</blockquote>
<table class="table table-striped table-bordered table-hover">
    <tbody><tr> 
     	<th><?php echo __("ID");?></th> 
     	<th><?php echo __("Slug");?></th> 
   		<th><?php echo __("Memo");?></th> 
        <th style="width:200px"> 
	 		<a href="<?php echo url('query/site/edit');?>" title="<?php echo __('Create');?>">	
				<i class="fa fa-plus-square"></i>
			</a> 
     	</th>
      
    </tr>
    <?php if($posts){foreach($posts as $vo){?> 
    <tr>
    	<td><?php echo $vo->id;?></td> 
        <td><?php echo $vo->slug;?></td> 
        <td><?php echo $vo->memo;?></td> 
        <td>     
    		
			<a href="<?php echo url('query/site/edit',['id'=>$vo->id]);?>">		
				<i class="fa fa-edit"></i>
			</a>  
		
        </td>
       
    </tr>
    <?php }}?>
</tbody>
</table>
<?php echo $paginate;?>
	
 
<?php $this->end();?>