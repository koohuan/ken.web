<?php if($search){
HTML::code("
	$('#search').click(function(){
		var obj = $('#search_form');
		var i = obj.css('display');
		if(i=='block')
			obj.hide();
		else
			obj.show();
	});
");	
?>

<span class="glyphicon glyphicon-search" id='search' style="cursor:pointer;margin-top: 5px;margin-bottom: 10px;font-size: 24px;"></span>
	
<div class='well' id="search_form" style="display:none;">
	<?php 
	echo \Form::open('form',[
		'method'=>'GET'
	]);
	?>
	<?php foreach($search as $k=>$v){ $e = $v['element']?:'input';?>
		<?php echo \Form::label(__($v['label']?:$k));?> 
		<?php 
			unset($v['label']); 
			$v['class'] = 'form-control';
			echo \Form::$e($k,$v);
		?>
	<?php }?>
	<p>	
		<?php echo \Form::submit(__('search'),['class'=>'btn btn-primary']);?>
	</p>
	<?php echo \Form::close();?> 
</div>
<?php }?>


<table class="table table-bordered" id='table'>
      <thead>
        <tr>
    	  <?php foreach($label as $v){?>
         	 <th><?php echo __($v);?></th> 
          <?php }?>
          
          <?php if($btn){ $max = count($btn)*40;?>
          	  
          	  	  <th style="width:<?php echo $max;?>px;"> </th>
          	  
          <?php }?>
        </tr>
      </thead>
      <tbody>
      	<?php foreach($posts as $post){?>	
	        <tr>
	          <?php foreach($label as $field=>$v){
	          	  $op = $fields[$field]['option'];
	          	  $value = $post->$field;
	          	  if($op) $value = $op[$value];
	            ?>
	         	 <td><?php echo $value;?></td> 
	          <?php }?>
	          <?php if($btn){?>
	          	  <td>
		          	  <?php foreach($btn as $k=>$v){
		          	  	  $option = $v['option']?:[];
		          	  	  unset($str);
		          	  	  foreach($option as $_k=>$_v){
		          	  	  	$st .=$_k."=\"".$_v."\" ";
		          	  	  } 
		          	  	  $v[1] = str_replace('{id}',$post->id,$v[1]);
		          	  	  $url = url($v[0],$v[1]);
		          	  	  if($v[2]){
		          	  	  	 $k = $v[2][$post->$k]; 
		          	  	  }
		          	  	?>
		          	  	  <a href="<?php echo $url;?>" <?php echo $st;?> style='margin-right:15px;'>
		          	  	    <?php echo $k ;?>
		          	  	  </a>
		          	  	   
		          	  	   
		          	  <?php }?>
	          	  </td>
	          <?php }?>
	        </tr>
        <?php }?>
      </tbody>
    </table>
    
    <?php echo $paginate;?>


