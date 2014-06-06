<?php   

$this->layout('default'); 
?>
	
<?php echo $this->start('content');?>
 

<div class="panel panel-default">
      <!-- Default panel contents -->
      <div class="panel-heading"><?php echo __('User Manage');?></div>
      <div class="panel-body">
        <p>
    		<?php echo __('User manage control user auth');?>
    	</p>
      </div>

      <!-- Table -->
      <table class="table">
        <thead>
          <tr>
            <th><?php echo __('Name');?></th>
            <th><?php echo __('Email');?></th>
          	<th><?php echo __('Group');?></th>
            <th><?php echo __('Create At');?></th>
            <th><?php echo __('Action');?></th>
          </tr>
        </thead>
          
        <tbody>
          <?php foreach($posts as $v){?>	
          <tr> 
 	            <td><?php echo $v->username;?></td>
	            <td><?php echo $v->email;?></td>
	            <td><?php $g = \AuthController::get_group_by_user_id($v->id);if($g)echo \Arr::to_str($g," \n ");?></td>
	            <td><?php echo $v->create_at;?></td> 
	            <td>
	          
	          		<a href="<?php echo url('admin/user/group',['id'=>$v->id]);?>"><span class="glyphicon glyphicon-user"></span></a>
	          		 
	          		<a href="<?php echo url('admin/user/update',['id'=>$v->id]);?>"><span class="glyphicon glyphicon-edit"></span></a>
	          			
	          		<a href="<?php echo url('admin/user/delete',['id'=>$v->id]);?>" onclick="return confirm('<?php echo __('Confirm Remove?');?>');"><span class="glyphicon glyphicon-remove"></span></a>

	          
	          
	          	</td> 
          </tr>
          <?php }?> 
        </tbody>
      </table>
    </div>
    	  
 
		
<?php echo $this->end();?>
