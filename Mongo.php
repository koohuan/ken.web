<?php
/** 
 	
	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014
*/
namespace Ken\Web; 
class Mongo{ 
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
	public function __construct($connection = 'mongodb://localhost:27017' ,$db , $arr = []){ 
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
	
	/**
	array(
	    array('dc' => 'east', 'use' => 'reporting'),
	    array('dc' => 'west'),
	    array(),
	)
	*/
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
	
	//返回SQL 信息
	function log(){
		 return static::$log;
	}
	/**
		select 查寻字段
	*/
	function select($str = "*"){
		$this->ar['SELECT'] = $str;
		return $this;
	}
	
	/**
		统计 
	*/
	function count($str = "count(*) num"){
		$this->ar['SELECT'] = $str;
		return $this;
	}
 
	/**
	 	insert('user',['username'=>'admin','email'=>'test@msn.com'])
	*/
	function insert($table,$arr = []){  
		return $this->db->$table->insert($arr);  
	} 
	/**
		$db->delete('posts','id=?',[1]); 
	*/
	function delete($table,$condition=null,$value=[]){ 
		$this->sql = "DELETE FROM $table ";
		if($condition)
			$this->sql .= "WHERE $condition ";
		if($value){
			$this->_to_sql($value); 
		}   
		return $this->exec();
	}
	/**
	   $db->update('posts',['name'=>'abc2'],'id=?',[1]);
		
	   $table 数据库表名
	   $set 数组，需要更新的 [字段=>值]
	   $condition 字符串   必须使用占位符 `?` 如 'id = ?'
	   $value 数组 如 [1] 
	   
	*/
	function update($table,$set = [] ,$condition=null,$value=[]){
		$this->_to_sql($set);
		$this->sql = "UPDATE $table SET ".$this->key;
		if($condition)
			$this->sql .= "WHERE $condition ";
		if($value){
			$this->value = array_merge($this->value,$value);
		}  
		return $this->exec();
	}
	
	function table($table){ 
		$this->ar['TABLE'] = $table; 
		return $this;
	}
 	function cache($time=0){ 
		$this->cache_time = $time;
		$this->cache_id = 'mysql_'.json_encode($this->ar).$this->cache_time;
		return $this;
	}
	
	protected function _one(){
		$this->_query();
		return $this->query->fetch(\PDO::FETCH_OBJ);
	}
 	/**
		$db->table('posts')
			->where('name=?',['abc'])  
			->one(); 
	*/
	function one(){  
		if(isset($this->cache_time)){
			$id = md5($this->cache_id.'one');
			$value = F::get('cache')->get($id); 
			if(!$value){
				$value = $this->_one();
				F::get('cache')->set($id,$value);  
			}
		} else{
			$value = $this->_one();
		}
	 
		return $value; 
	}
	protected function _all(){
		$this->_query(); 
		return $this->query->fetchAll(); 
	}
	/**
		$db->table('posts')
			->where('name=?',['abc'])  
			->all(); 
	*/
	function all(){  
		if(isset($this->cache_time)){
			$id = md5($this->cache_id.'all');
			$value = F::get('cache')->get($id); 
			if(!$value){ 
				$value = $this->_all(); 
				F::get('cache')->set($id,$value);  
			}
		}else{
			$value = $this->_all(); 
		}
		return $value;
	}
	/**
		支持纯SQL
	*/
	function query($sql,$value=[]){
		$this->sql = $sql;
		$this->value = $value;   
		$this->exec(); 
		return $this;
	}
	/**
	* exec sql 
	*/
	protected function _query(){  
		$value = [];
		if(!$this->ar['TABLE']) return $this;
		$sql = "select ".($this->ar['SELECT']?:'*')." FROM ".$this->ar['TABLE'];
		unset($this->ar['SELECT'],$this->ar['TABLE']);
		$sql .= " WHERE 1=1 "; 
 		if($this->ar){
			foreach($this->ar AS $key=>$condition){
				if(is_array($condition)){
					foreach($condition as $str=>$vo){
				 		$sql .= $k." ".$str." "; 
				 	 	$value = array_merge($value,$vo);
				 	}
				}else{
					$sql .= $key." ". $condition." ";
			 	}
			}
		}  
		//使用后要删除 $this->ar
		unset($this->ar,$this->where);
		$this->sql = $sql;
		$this->value = $value;  
		try { 
			$this->exec(); 
			return $this;
		}catch (Exception $e) {
		    return false;
		} 
	}
	/**
		execute sql
	*/
	protected function exec($insert = false){
		//记录日志
		if($this->debug === true){
			$log = ['sql'=>$this->sql,'value'=>$this->value];
		} 
		try {  
			$this->query = $this->pdo->prepare($this->sql , [\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY]);  
	        $this->pdo->beginTransaction();  
	        if(!$this->query->execute( $this->value )){ 
	        	$info = $this->query->errorInfo()[2]; 
	        	$info = 'SQL ERROR:'.$info."<br>SQL:".$this->sql;
	        	throw new \Exception($info,500); 
	        }
	        $id = $this->pdo->lastInsertId();
	     	$this->pdo->commit();   
	    } catch(PDOExecption $e) {   
 	        $this->pdo->rollback();  
	    } 
	    static::$log[] = $log; 
	    return $id?:false; 
	}
	
	function __call ($name ,$arg = [] ){
		$name = strtoupper($name);
		$key = $arg[0];
		$vo = $arg[1];
		$name = str_replace('_',' ',$name);  
		if(strpos($name,'WHERE')!==false){  
			$arr = explode(' ',$name);
			if(!$this->where){
				$key = "AND ".$key;
				$this->where = true;
			}else{
				if($arr[1]){ 
					$key = $arr[0]." ".$key;
				}else{
					$key = "AND ".$key;
				}
			}
		}
		if($vo){
			$this->ar[$name][$key] = $vo;
		}else if($key){  
			$this->ar[$name] = $key;
		}
	 
		return $this;
	} 
	
	
}