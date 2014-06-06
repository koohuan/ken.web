<?php
/**
 	backup database
	@auth Kang Sun <68103403@qq.com>
 	@date 2014
	
*/
namespace Ken\Web\doc\module\admin;  
 
class backupdb extends \AuthController{  
  	public $path;
	public $bin;
	public $name;
	public $pwd;
	public $db_name;
	public $host;
	public $file;
 
	function init(){ 
		parent::init();
		\Ken\Web\Menu::set('admin.backupdb');
		$this->title = __('backup database'); 
		$this->path = base_path().'/data';
		$one = \DB::w()->sql("SHOW VARIABLES LIKE '%basedir%'")->one()->value; 
		$this->bin = $one.'/bin/';
		$h = \DB::w()->connect;
		$dsn = $h['dsn'];
		$n = explode(';',$dsn); 
		$this->host = substr( $dsn ,strrpos($dsn,'=') +1);   
		$this->db_name = substr( $n[0] ,strpos($dsn,'=')+1); 
		$this->name = $h['user'];
		$this->pwd = $h['pwd'];
	 	$dir = $this->path."/".$this->db_name."_";
		$this->file = $dir.date('Ymd-H-i-s',time()).'.sql'; 
	}
  	/**
		列表
	*/ 
	function indexAction(){   
		$list = scandir($this->path);
		foreach($list as $vo){
			if($vo !="."&& $vo !=".." && $vo !=".svn" && $vo !=".git")
			{
				$rows[$vo]=filemtime($this->path.'/'.$vo);
			}
		}
		if($rows)
			$rows = array_reverse($rows);  
	 	$this->view('backupdb',[
	 		'rows'=>$rows,
	 		'dir'=>$this->path
	 	]);
	  
	} 
 
	function doAction(){
		$id = $_GET['id'];
		$file = $_GET['file']; 
		switch($id){ 
 			case 'store':
				$sql = $this->bin."mysqldump -h ".$this->host." -u".$this->name." -p".$this->pwd." ".$this->db_name." >  ".$this->file; 
				$msg = __("Backup Database Success");
				break; 
				
			/*case 'restore':
				$sql = $this->bin."mysql -h ".$this->host." -u".$this->name." -p".$this->pwd." ".$this->db_name." <  ".$this->file;  
				$msg = __("Restore Database Success");
				break; */
		} 
		@exec($sql);
		\Session::flash('success',$msg);
		$this->redirect(url('admin/backupdb/index'));
	}
	 
	
	
	 
}