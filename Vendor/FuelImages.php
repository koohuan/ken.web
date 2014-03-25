<?php 
/**
    
    http://www.fuelphp.com/docs/classes/image.html
    
    
 	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
*/
namespace Ken\Web\Vendor;
class FuelImages{
 
  	/**
  	$opt
  	
  	'bgcolor' => '#f00', // Set the background color red
    'filetype' => 'jpg', // Output as jpeg.
    'quality' => 75,
    'actions' => array(
        array('crop_resize', 200, 200),
        array('watermark', '$1'), // Note the $1 is a variable.
        array('output', 'png')
    )
  	*/
  
  	
	static function init($opt = [] ) {
		import(__DIR__.'/FuelImages/Fuel_Image.php');
	 	return \Fuel_Image::forge($opt);   
	 	 
	} 
 
	
	
 
 
}