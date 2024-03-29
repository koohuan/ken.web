<?php   
/**
 * Part  of the Fuel framework.
 *
 * @package    Fuel
 * @version    1.6
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2013 Fuel Development Team
 * @link       http://fuelphp.com
 */

 

class Fuel_Image
{

	protected static $_instance = null;

	/**
	 * Holds the config until an instance is initiated.
	 *
	 * @var  array   Config options to be passed when the instance is created.
	 */
	protected static $_config = array();

	/**
	 * Initialize by loading config
	 *
	 * @return void
	 */
	public static function _init()
	{
		 
	}

	/**
	 * Creates a new instance for static use of the class.
	 *
	 * @return  Image_Driver
	 */
	protected static function instance()
	{
		if (self::$_instance == null)
		{
			self::$_instance = self::forge(self::$_config);
		}
		return self::$_instance;
	}
	static function __config(){
		$default = dirname(__FILE__)."/config/image.php";
		$inner = dirname(__FILE__)."/config.php";
		if(file_exists($default)){
			$config = include $default;
		}else{
			$config = include $inner;
		}
		return $config;
	}
	/**
	 * Creates a new instance of the image driver
	 *
	 * @param   array  $config
	 * @return  Image_Driver
	 */
	public static function forge($config = array(), $filename = null)
	{
		!is_array($config) and $config = array();
		
		$config = array_merge(self::__config(), $config);
 
		$protocol = ucfirst( ! empty($config['driver']) ? $config['driver'] : 'gd');
	 
		import(__DIR__."/Fuel_".$protocol.'.php');
		$class = "\Fuel_".$protocol;
		if ($protocol == 'Driver' || ! class_exists($class))
		{
			return 'imagecache is not a valid driver for image manipulation.';
		} 
		$return = new $class($config); 
		if ($filename !== null)
		{
			$return->load($filename);
		}
		return $return;
	}

	/**
	 * Used to set configuration options.
	 *
	 * Sending the config options through the static reference initalizes the
	 * instance. If you need to send a driver config through the static reference,
	 * make sure its the first one sent! If errors arise, create a new instance using
	 * forge().
	 *
	 * @param   array   $config   An array of configuration settings.
	 * @return  Image_Driver
	 */
	public static function config($index = array(), $value = null)
	{
		if (self::$_instance === null)
		{
			if ($value !== null)
				$index = array($index => $value);
			if (is_array($index))
				self::$_config = array_merge(self::$_config, $index);
			self::instance();
			return self::instance();
		} else {
			return self::instance()->config($index, $value);
		}
	}

	/**
	 * Loads the image and checks if its compatable.
	 *
	 * @param   string  $filename							The file to load
	 * @param   string  $return_data					Decides if it should return the images data, or just "$this".
	 * @param   mixed		$force_extension			Whether or not to force the image extension
	 * @return  Image_Driver
	 */
	public static function load($filename, $return_data = false, $force_extension = false)
	{
		return self::instance()->load($filename, $return_data, $force_extension);
	}

	/**
	 * Crops the image using coordinates or percentages.
	 *
	 * Absolute integer or percentages accepted for all 4.
	 *
	 * @param   integer  $x1  X-Coordinate based from the top-left corner.
	 * @param   integer  $y1  Y-Coordinate based from the top-left corner.
	 * @param   integer  $x2  X-Coordinate based from the bottom-right corner.
	 * @param   integer  $y2  Y-Coordinate based from the bottom-right corner.
	 * @return  Image_Driver
	 */
	public static function crop($x1, $y1, $x2, $y2)
	{
		return self::instance()->crop($x1, $y1, $x2, $y2);
	}

	/**
	 * Resizes the image. If the width or height is null, it will resize retaining the original aspect ratio.
	 *
	 * @param   integer  $width   The new width of the image.
	 * @param   integer  $height  The new height of the image.
	 * @param   boolean  $keepar  Defaults to true. If false, allows resizing without keeping AR.
	 * @param   boolean  $pad     If set to true and $keepar is true, it will pad the image with the configured bgcolor
	 * @return  Image_Driver
	 */
	public static function resize($width, $height, $keepar = true, $pad = false)
	{
		return self::instance()->resize($width, $height, $keepar, $pad);
	}

	/**
	 * Resizes the image. If the width or height is null, it will resize retaining the original aspect ratio.
	 *
	 * @param   integer  $width   The new width of the image.
	 * @param   integer  $height  The new height of the image.
	 * @return  Image_Driver
	 */
	public static function crop_resize($width, $height)
	{
		return self::instance()->crop_resize($width, $height);
	}

	/**
	 * Rotates the image
	 *
	 * @param   integer  $degrees  The degrees to rotate, negatives integers allowed.
	 * @return  Image_Driver
	 */
	public static function rotate($degrees)
	{
		return self::instance()->rotate($degrees);
	}

	/**
	 * Creates a vertical / horizontal or both mirror image.
	 *
	 * @access public
	 * @param string $direction 'vertical', 'horizontal', 'both'
	 * @return Image_Driver
	 */
	public static function flip($direction)
	{
		return self::instance()->flip($direction);
	}

	/**
	 * Adds a watermark to the image.
	 *
	 * @param   string   $filename  The filename of the watermark file to use.
	 * @param   string   $position  The position of the watermark, ex: "bottom right", "center center", "top left"
	 * @param   integer  $padding   The spacing between the edge of the image.
	 * @return  Image_Driver
	 */
	public static function watermark($filename, $position, $padding = 5)
	{
		return self::instance()->watermark($filename, $position, $padding);
	}

	/**
	 * Adds a border to the image.
	 *
	 * @param   integer  $size   The side of the border, in pixels.
	 * @param   string   $color  A hexidecimal color.
	 * @return  Image_Driver
	 */
	public static function border($size, $color = null)
	{
		return self::instance()->border($size, $color);
	}

	/**
	 * Masks the image using the alpha channel of the image input.
	 *
	 * @param   string  $maskimage  The location of the image to use as the mask
	 * @return  Image_Driver
	 */
	public static function mask($maskimage)
	{
		return self::instance()->mask($maskimage);
	}

	/**
	 * Adds rounded corners to the image.
	 *
	 * @param   integer  $radius
	 * @param   integer  $sides      Accepts any combination of "tl tr bl br" seperated by spaces, or null for all sides
	 * @param   integer  $antialias  Sets the antialias range.
	 * @return  Image_Driver
	 */
	public static function rounded($radius, $sides = null, $antialias = null)
	{
		return self::instance()->rounded($radius, $sides, $antialias);
	}

	/**
	 * Turns the image into a grayscale version
	 *
	 * @return  Image_Driver
	 */
	public static function grayscale()
	{
		return self::instance()->grayscale();
	}

	/**
	 * Saves the image, and optionally attempts to set permissions
	 *
	 * @param   string  $filename     The location where to save the image.
	 * @param   string  $permissions  Allows unix style permissions
	 * @return  Image_Driver
	 */
	public static function save($filename = null, $permissions = null)
	{
		return self::instance()->save($filename, $permissions);
	}

	/**
	 * Saves the image, and optionally attempts to set permissions
	 *
	 * @param   string  $prepend      The text to add to the beginning of the filename.
	 * @param   string  $applicationend       The text to add to the end of the filename.
	 * @param   string  $permissions  Allows unix style permissions
	 * @return  Image_Driver
	 */
	public static function save_pa($prepend, $applicationend = null, $permissions = null)
	{
		return self::instance()->save_pa($prepend, $applicationend, $permissions);
	}

	/**
	 * Outputs the file directly to the user.
	 *
	 * @param   string  $filetype  The extension type to use. Ex: png, jpg, bmp, gif
	 * @return  Image_Driver
	 */
	public static function output($filetype = null)
	{
		return self::instance()->output($filetype);
	}

	/**
	 * Returns  sizes for the currently loaded image, or the image given in the $filename.
	 *
	 * @param   string  The location of the file to get sizes for.
	 * @return  object  An object containing width and height variables.
	 */
	public static function sizes($filename = null)
	{
		return self::instance()->sizes($filename);
	}

	/**
	 * Reloads the image.
	 *
	 * @return  Image_Driver
	 */
	public static function reload()
	{
		return self::instance()->reload();
	}
}
