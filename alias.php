<?php
 
$alias = [  
	'Ken\Web\Config'=>'Config',
 	'Ken\Web\Helper'=>'Helper',
	'Ken\Web\Lang'=>'Lang',
	'Ken\Web\Arr'=>'Arr',
	'Ken\Web\Image'=>'Image',
	'Ken\Web\User'=>'User',
	'Ken\Web\Auth'=>'Auth',
	'Ken\Web\Mail'=>'Mail',
	'Ken\Web\Cache'=>'Cache',
	'Ken\Web\CleanVendor'=>'CleanVendor',
	'Ken\Web\Controller'=>'Controller',
	'Ken\Web\Cookie'=>'Cookie',
	'Ken\Web\Crypt'=>'Crypt',
	'Ken\Web\Curl'=>'Curl',
	'Ken\Web\DB'=>'DB',
	'Ken\Web\Event'=>'Event',
	'Ken\Web\F'=>'F',
	'Ken\Web\File'=>'File',
	'Ken\Web\Form'=>'Form',
	'Ken\Web\HTML'=>'HTML',
	'Ken\Web\Img'=>'Img',
	'Ken\Web\Input'=>'Input',
	'Ken\Web\Log'=>'Log',
	'Ken\Web\Paginate'=>'Paginate',
	'Ken\Web\Response'=>'Response',
	'Ken\Web\Route'=>'Route',
	'Ken\Web\Session'=>'Session',
	'Ken\Web\Str'=>'Str',
	'Ken\Web\Tree'=>'Tree',
	'Ken\Web\UserController'=>'UserController',
	'Ken\Web\AdminController'=>'AdminController',
	'Ken\Web\Validate'=>'Validate',
	'Ken\Web\View'=>'View',
	'Ken\Web\Widget'=>'Widget', 
];
foreach($alias as $k=>$v){
	class_alias($k,$v);
}

 