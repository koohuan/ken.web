<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo __('Login');?></title> 
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
     <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
     #form{width:300px;margin:auto;margin-top:20px;}
     .bg-info{height:50px;line-height:50px;text-align: center;}
    </style>
</style>
  </head>
  <body>

<blockquote style="width: 300px;
margin: auto;
margin-top: 20px;">
	<?php echo __('Login to backend manage');?>
</blockquote>	
<?php
 
	widget('form',[
		 'error'=>$error,
		 'fields'=>$element,
		 'submit'=>$button?:'save',
	]);
?>	 
 
</body>
</html>		
