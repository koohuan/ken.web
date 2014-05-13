<?php
/** 
 	Mongo DB
 	
 	
 	配置 文件在config/mongo.php
 	
 	return  [
	
		'w'=>["mongodb://localhost:27017","api-teebik-com" ,[]],
		'log'=> ["mongodb://localhost:27017","teebik-log" ,[]],
	];
	
	其中 log 为记录日志所用。如没有将默认使用第一个

	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014
*/
namespace Ken\Web; 
class Mo{ 
	public $pdo; 
	protected $query;
	protected $ar;
	// sql debug 默认关闭
	public $debug = false;
	static $log;
	protected $sql;
	protected $value;
	protected $key;
	public $active = false;
	public $where; 
 
	public $connect;
	public $cache_time;
	public $cache_id;  
	static $write;
	public function __construct($connection = 'mongodb://localhost:27017' ,$db = 'user' , $arr = []){ 
		if(!class_exists('MongoClient')) return false;
		try { 
 		    $this->pdo = new \MongoClient($connection,$arr);  
		    $this->active = true; 
		} catch ( \MongoConnectionException $e) { 
			$this->active = false;
			Log::MongoClient("MongoClient failed:".$connection);
		    return false;
		}
		$this->db = $this->pdo->$db;
		return $this;
	}  
	static function w($default = 'w'){
		if(!isset(static::$write)){
			$db = Config::load('mongo'); 
			$config = $db[$default];
			if(!$config) $config = $db[0];
			static::$write = new Static($config[0],$config[1],$config[2]);  
		}
		return static::$write;
	}
	function set_read( $arr = []){
		$this->pdo->setReadPreference(MongoClient::RP_SECONDARY, $arr);
	}
	function set_write( $name ,$port = 3000){
		$this->pdo->setWriteConcern($name, $port);
	} 
	function lists(){
		return $this->pdo->listDBs();
	} 
	function connects(){
		return $this->pdo->getConnections();
	} 
	/**
	 	insert('user',['username'=>'admin','email'=>'test@msn.com'])
	*/
	function insert($table,$arr = []){  
		return $this->db->$table->insert($arr);  
	} 
 
	
	
}