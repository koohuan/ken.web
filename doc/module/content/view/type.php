<?php  
$this->layout('default'); 
?>
	
<?php echo $this->start('content');?>
	<blockquote><?php echo __('Content Type');?> </blockquote>
	<p>
		<a href="<?php echo url('content/type/save');?>"><i class="fa-2x fa fa-plus-square"></i></a> 
 	</p>
 <table class="table table-striped table-bordered table-hover">
    <tbody><tr> 
        <th><?php echo __('Content');?></th> 
    	<th><?php echo __('Autoload');?> \classes\content</th>
     	<th><?php echo __('Mysql Table');?></th> 
     	<th><?php echo __('Description');?></th> 
        <th style="width:200px">
	 		<a href="<?php echo url('content/type/save');?>" title="<?php echo __('Create');?>">	
				<i class="fa fa-plus-square"></i>
			</a>
     	</th>
    </tr>
    <?php if($all){foreach($all as $v){?> 
    <tr>
        
        <td><?php echo $v->name;?></td>
        <td><?php echo $v->load;?></td>	
        <td><?php echo $v->slug;?></td>
        <td><?php echo $v->memo;?></td>
        <td>    
    		<a href="<?php echo url('content/type/set',['id'=>$v->id]);?>">		
    			<i class="fa fa-cog"></i>
    		</a>
    				
			<a href="<?php echo url('content/type/save',['id'=>$v->id]);?>">		
				<i class="fa fa-edit"></i>
			</a> 
        </td>
        
    </tr>
    <?php }}?>
</tbody>
</table>
 
		
<?php echo $this->end();?>
