<?php 
/**
    http://www.fuelphp.com/docs/classes/image.html
  
    使用方法
    
    $a = public_path().'/upload/1.png';
	$b = public_path().'/upload/2.png';
	$op = ['quality'=>75];
	Image::init($op)->load($a)->output($b);
	
	Image::init($op)->load($a)->save($b);
    
 	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
*/
namespace Ken\Web;
class Image extends \Ken\Web\Vendor\FuelImages{}