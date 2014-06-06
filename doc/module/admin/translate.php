<?php
/**
 	界面多语言翻译
	@auth Kang Sun <68103403@qq.com>
 	@date 2014
	
*/
namespace Ken\Web\doc\module\admin;  
use Ken\Web\Vendor\Spyc;
class translate extends \AuthController{ 
	 
	function indexAction(){   
		\Ken\Web\Menu::set('admin.translate');
		
		$this->title = __("tanslate language");
		$dir = base_path().'/messages';
		$id = $_GET['id']?:'/zh_CN/app.php';
		$url = url('admin/translate/index',['id'=>$id]);
		$file = $dir.$id;
		$fs = \File::find($dir)['file']; 
		$vs = [];
		if($fs){
			foreach($fs as $v){
				$vs[] = str_replace($dir,"",$v); 
			}
		}
	 
		$post = Spyc::YAMLDump(include ($file) );  
		if($_POST){
				$txt = str_replace("：",":",$_POST['txt']);
			 	$d = Spyc::YAMLLoadString($txt);
			 	file_put_contents($file,"<?php \nreturn  ".var_export($d,true).";");  
			 	\Session::flash('success',__('Success'));
			 	$this->redirect(url('admin/translate/index')); 
	 	}
	   
	 	$this->view('translate',[
	 		'post'=>$post,
	 		'vs'=>$vs,
	 		'url'=>$url,
	 	]);
	 	
	}
	 
}