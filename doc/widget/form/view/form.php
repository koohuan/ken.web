<?php 
	use Ken\Web\Form;
	echo Form::open('form',[
		'method'=>'POST', 
	]);?>

<?php if($error){?>
	<div class="alert alert-danger"><?php echo $error;?></div>
<?php }?>
<?php foreach($fields as $k=>$v){ $e = $v['element']?:'input';?>
 
		<?php echo Form::label(__(ucfirst($v['label']?:$k)));?> 
		<p>
		<?php 
			unset($v['label']); 
			if($e=='select'){
				$v['style'] = "width:200px";
			}else
				$v['class'] = 'form-control';
			echo Form::$e($k,$v);
		?>
 		</p>
<?php }?>

<p>	
	<?php echo Form::submit(__(ucfirst($submit)),['class'=>'btn btn-default']);?>
</p>	
<?php echo Form::close();?>