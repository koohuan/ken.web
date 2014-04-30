<?php
/** 
 	Mongo DB
 	
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
	protected $dsn;
	public $connect;
	public $cache_time;
	public $cache_id;  
	static $write;
	public function __construct($connection = 'mongodb://localhost:27017' ,$db = 'user' , $arr = []){ 
		try {
			$this->dsn = $connection; 
 		    $this->pdo = new \MongoClient($connection,$arr); 
		    $this->active = true; 
		} catch ( Exception $e) {  
			$this->active = false;
		    return false;
		}
		$this->db = $this->pdo->$db;
		return $this;
	}  
	static function w(){
		if(!isset(static::$write)){
			$db = Config::load('mongo'); 
			$config = $db[0];
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