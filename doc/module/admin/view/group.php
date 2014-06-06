<?php   

$this->layout('default'); 
 
?>
	
<?php echo $this->start('content');?>

 
<p>
	<a href="<?php echo url('admin/group/save');?>"><i class="fa-2x fa fa-plus-square"></i></a> 
</p >
<div class="panel panel-default">
      <!-- Default panel contents -->
      <div class="panel-heading"><?php echo __('Group Manage');?></div>
      <div class="panel-body">
        <p>
    		<?php echo __('Group manage bind access');?>
    	</p>
      </div>

      <!-- Table -->
      <table class="table">
        <thead>
          <tr>
            <th><?php echo __('Name');?></th> 
            <th><?php echo __('Create At');?></th> 
            <th><?php echo __('Action');?></th>
          </tr>
        </thead>
          
        <tbody>
          <?php if($posts){foreach($posts as $v){?>	
          <tr> 
 	            <td><?php echo $v->name;?></td>
 	            <td><?php echo $v->create_at;?></td> 
	            <td>  
	          		<a href="<?php echo url('admin/group/access',['id'=>$v->id]);?>"><i class="fa fa-cog"></i></a>
 	          		<a href="<?php echo url('admin/group/delete',['id'=>$v->id]);?>"><span class="glyphicon glyphicon-remove"></span></a> 
 	          	</td> 
          </tr>
          <?php }}?> 
        </tbody>
      </table>
    </div>
    	  

 		
<?php echo $this->end();  ?>
