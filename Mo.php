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
	
	function insertBatch($table,$arr = [] ,$options = []){  
		$options = array_merge(
            [
                'safe' => true
            ],
            $options
        ); 
		return $this->pdo->$table->batchInsert($arr , $options);  
	} 
	/**
	http://www.bumao.com/index.php/2010/08/mongodb_php_update.html
	$inc

	如果记录的该节点存在，让该节点的数值加N；如果该节点不存在，让该节点值等于N

	设结构记录结构为 array(’a'=>1,’b'=>’t'),想让a加5，那么：

	$coll->update(

	array(’b'=>’t'),

	array(’$inc’=>array(’a'=>5)),

	)

	$set

	让某节点等于给定值

	设结构记录结构为 array(’a'=>1,’b'=>’t'),b为加f，那么：

	$coll->update(

	array(’a'=>1),

	array(’$set’=>array(’b'=>’f')),

	)

	$unset

	删除某节点

	设记录结构为 array(’a'=>1,’b'=>’t')，想删除b节点，那么：

	$coll->update(

	array(’a'=>1),

	array(’$unset’=>’b'),

	)

	$push

	如果对应节点是个数组，就附加一个新的值上去；不存在，就创建这个数组，并附加一个值在这个数组上；如果该节点不是数组，返回错误。

	设记录结构为array(’a'=>array(0=>’haha’),’b'=>1)，想附加新数据到节点a，那么：

	$coll->update(

	array(’b'=>1),

	array(’$push’=>array(’a'=>’wow’)),

	)

	这样，该记录就会成为：array(’a'=>array(0=>’haha’,1=>’wow’),’b'=>1)

	$pushAll

	与$push类似，只是会一次附加多个数值到某节点

	$addToSet

	如果该阶段的数组中没有某值，就添加之

	设记录结构为array(’a'=>array(0=>’haha’),’b'=>1)，如果想附加新的数据到该节点a，那么：

	$coll->update(

	array(’b'=>1),

	array(’$addToSet’=>array(’a'=>’wow’)),

	)

	如果在a节点中已经有了wow,那么就不会再添加新的，如果没有，就会为该节点添加新的item——wow。

	$pop

	设该记录为 array(’a'=>array(0=>’haha’,1=>’wow’),’b'=>1)

	删除某数组节点的最后一个元素:

	$coll->update(

	array(’b'=>1),

	array(’$pop=>array(’a'=>1)),

	)

	删除某数组阶段的第一个元素

	$coll->update(

	array(’b'=>1),

	array(’$pop=>array(’a'=>-1)),

	)

	$pull

	如果该节点是个数组，那么删除其值为value的子项，如果不是数组，会返回一个错误。

	设该记录为 array(’a'=>array(0=>’haha’,1=>’wow’),’b'=>1)，想要删除a中value为haha的子项：

	$coll->update(

	array(’b'=>1),

	array(’$pull=>array(’a'=>’haha’)),

	)

	结果为： array(’a'=>array(0=>’wow’),’b'=>1)

	$pullAll

	与$pull类似，只是可以删除一组符合条件的记录。
	
	*/
	function update($table,$arr = [] ,$where,$condition,$options = []){  
		$where['id'] = new MongoId('47cc67093475061e3d9536d2'); 
		$this->pdo->findOne($where);
		return $this->pdo->$table->update($arr);  
	} 
	
 
	
	
}