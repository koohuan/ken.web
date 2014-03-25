图片处理
========

http://www.fuelphp.com/docs/classes/image.html
	
	$a = public_path().'/upload/1.png';
	$b = public_path().'/upload/2.png';
	$op = ['quality'=>75];
	Image::init($op)->load($a)->output($b);
	
	Image::init($op)->load($a)->save($b);

可选设置
	
	watermark_alpha 75
	bgcolor
	driver           gd
	quality          100
	temp_dir         /tmp/
	imagemagick_dir  /usr/bin/
	temp_append      fuel_image
	debug            false


	'bgcolor' => '#f00', // Set the background color red
        'filetype' => 'jpg', // Output as jpeg.
        'quality' => 75,
        'actions' => array(
            array('crop_resize', 200, 200),
            array('watermark', '$1'), // Note the $1 is a variable.
            array('output', 'png')
        )

actions 说明 

	resize($width, $height = null, $keepar = true, $pad = false)
	rotate($degrees)
	flip($direction)  vertical/horizontal/both
	watermark($filename, $position, $padding = 5) 
	watermark('watermark.ext', "top left", 15);
	watermark('watermark.ext', "bottom right");
	watermark('watermark.ext', "center middle");

	// "center middle" is identical to "center center", "middle middle", or "middle center"
	border($size, $color = null)
	border(5, '#FF0000')
	mask($maskimage) //Applies a mask to the image by blending the alpha channel of the mask with those of the loaded image.
	mask('mask.ext');
	rounded($radius, $sides = null, $antialias = null)
	rounded(10, "tl tr");
	// Returns 'jpg'
	$ext = Image::load('uploaded_file.jpg')
	    ->extension();
	// Save a PNG as a JPG
	Image::load('placeholder.png')
	    ->output($ext);
	
	//Saves the image to the same location with a prepended and/or appended filename, and optionally attempts to set permissions.
	save_pa($prepend, $append = null, $extension = null, $permissions = null)
	save_pa('prepend_', '_append');