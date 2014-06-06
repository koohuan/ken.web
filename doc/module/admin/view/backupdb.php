<?php  
$this->layout('default'); 
?>
	
<?php echo $this->start('content');?>
<p>	
	<a class='btn btn-primary ' href="<?php echo url('admin/backupdb/do',array('id'=>'store'));?>">
		<?php echo __('Backup');?>
	</a>
</p>	
 
	
<?php if($rows){?>
<table class="table table-bordered">
<thead>
<tr>
<th><?php echo __('Name');?></th>
<th><?php echo __('Time');?></th>
<!--<th><?php echo __('Restore');?></th>-->
</tr>
</thead>
<tbody>
<?php foreach($rows as $vo=>$time){?>
<tr>
<td><?php echo $vo;?>&nbsp;&nbsp;[<?php echo File::size($dir.'/'.$vo);?>]</td>
<td><?php echo date('Y-m-d H:i:s',$time);?></td>
	
<!--<td><a class='label label-primary' href="<?php echo url('admin/backupdb/do',['id'=>'restore',file=>$dir.'/'.$vo]);?>" onclick="return confirm('<?php echo __('Confirm recovery databse?');?>');" ><?php echo __('Restore');?></a></td>    -->
<?php }?>
</tbody>
</table>
<?php }?>
<?php echo $this->end();?>
