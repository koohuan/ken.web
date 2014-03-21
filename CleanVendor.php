<?php 
/**
    清除Composer vendor 中指定的文件夹 或 文件名
  	
  	$route->get('vendor',function(){
		$dir = base_path().'/vendor';
		$obj = new CleanVendor($dir);
		$obj->run();
		 
	});

 	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
 */
namespace Ken\Web;
class CleanVendor
{ 
	public $find;
	public $dir = [
		'doc',
		'docs',
		'test',
		'tests',
 		'test-suite',
 		'notes',  
	];
	public $file = [ 
		'.gitattributes',
		'composer.json',
		'readme.md',
		'readme',
		'rakefile',
		'.gitignore',
		'changelog.md',
		'build.xml',
		'create_pear_package.php',
		'package.xml.tpl',
		'changes',
		'version',
		'readme.git',
		'.travis.yml',
		'readme.md',
		'phpunit.xml.dist',
		'license', 
	];
 	function __construct($vendor_dir){
 		$this->find = $vendor_dir;
 	}
	function run(){ 
 		$li = File::find($this->find);
 		$dir = $li['dir'];
 		foreach($dir as $v){
 			$name = substr($v,strrpos($v,'/')+1);
 			$name = strtolower($name);
 			if(is_dir($v) && in_array($name,$this->dir))
 				File::rmdir($v);
 		}
 	 
 		$file = $li['file'];
 		foreach($file as $v){
 			$name = substr($v,strrpos($v,'/')+1);
 			$name = strtolower($name); 
 			if(file_exists($name) && in_array($name,$this->file)){ 
 				unlink($v);
 				if(substr($v,-4) =='.git')
 					File::rmdir($v);
 			}
 		}
 		echo "Clean Up Vendor Finished!";
 	} 
	
 
}