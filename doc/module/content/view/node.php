<?php  
$this->layout('default'); 
HTML::js(base_url().'js/jquery.ajaxfileupload.js');
HTML::code("
	.ajax_file {margin-top:3px;margin-right:5px;float:left;}
	label { 
clear: both;
}
");
HTML::code("
	$('.fremove').click(function(){
		$(this).parent('div.ajax_file').remove();
	});
	$( '.handle' ).sortable();
");
?>
	
<?php echo $this->start('content');?>
	<blockquote><?php echo $post->name;?> <small><?php echo $post->slug;?></small></blockquote>
	
	<div id='div'></div>
	<?php echo \Form::open('form');?> 
		<p>
			
			<a href="<?php echo url('content/node/save',['id'=>$_GET['id']]);?>" title="<?php echo __('Create');?>">	
				<i class="fa-2x fa fa-plus-square"></i>
			</a>
				
			<a href="<?php echo url('content/node/index',['id'=>$post->id]);?>"><i class="fa-2x fa fa-list-ul"></i></a>
		</p>
 
	  <?php echo $form; ?>
	  <p style='clear:both;'>
		  <button style="margin-top: 20px;" type="submit" id='submit_btn' class="btn btn-default">
		  	  <?php echo __('Save');?>
		  </button>
	  </p>
	<?php echo \Form::close();  
	$info = $_GET['nid']?__('Modify Success'):__('Create Success');
	$reset = null;
	if(!$_GET['nid']) $reset = "clearForm:true,";
	HTML::code("  
		$('#form').ajaxForm({
		    target: '#div',
		    beforeSubmit:function(arr,form,opts){
		    	$('#submit_btn').attr('disabled','disabled').html('".__('Loading...')."');
		    },
		    ".$reset."
		    success:function(d){ 
		    	if(1==d)
		    		$('#div').html('".$info."').removeClass('bg-danger').addClass('bg-success').addClass('info').css('margin-bottom','5px').fadeOut(3000);
		    	else
		    		$('#div').html(d).removeClass('bg-success').addClass('bg-danger').css('margin-bottom','5px').fadeOut(3000); 
		    	$('#submit_btn').removeAttr('disabled').html('".__('New Save')."');
		    },
		    error:function(data){alert(data.message);
		    	$('#submit_btn').removeAttr('disabled').html('".__('Try Save')."');
		    }
		});
	");
	?>
				
<?php echo $this->end();?>
