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

<blockquote><?php echo __('User Bind Group');?>
	<small>
		<?php echo $user->username;?>
	</small>
</blockquote>


<?php echo Form::open();?>
	<p>
		<?php echo Form::select('group[]',[
				'option'=>$g,
				'value'=>$has,
				'multiple'=>"multiple" 
			]);?> 
	</p>
	<p>
		<input type="submit" value="<?php echo __('Save');?>">
	</p>
<?php echo Form::close();?>
 		
<?php echo $this->end();
HTML::code("
$('#add').click(function(){
	
});



");
 ?>
