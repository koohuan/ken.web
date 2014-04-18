<?php
/** 
	数据库设置，第一个为主库
	config/database.php
	
	return [
		["mysql:dbname=wei;host=127.0.0.1","test","test"],
		["mysql:dbname=wei2;host=127.0.0.1","test","test"],
	]; 
 	以下为非F操作			
 	数据库操作,如果有返回值 全为对象。
 	支持mysql 数据库。如order by ,请在 $db->table('table')->order_by('id desc');
 	其他方法依次类推
 	//主库
 	$db = DB::w();
 	//从库
 	$db = DB::r();
 	echo $db->insert('posts',['name'=>'test']);
 	
 	$db->update('posts',['name'=>'abcaaa'],'id=?',[1]);
 	
 	$db->delete('posts','id=?',[1]);
 	
	$r = $db->table('posts')
		->where('name=?',['abc']) 
		->order_by('id asc')
		->all(); 
		
	$r = $db->table('posts')
		->where('name=?',['abc'])  
		->one();  
	
	$connect 属性 [
	    	'dsn'=>$dsn,
	    	'user'=>$user,
	    	'pwd'=>$pwd,
	    	'active'=>$active, 
	    ];
 	
	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014
*/
namespace Ken\Web;
 
class DB{ 
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
	protected $user;
	protected $pwd;
	public $connect;
	public $cache_time;
	public $cache_id;
	//主从
	static $read;
	static $write;
	/**
	* 如In (?,?)
	*
	*/
	static function in($name){
		return str_repeat ('?, ',  count ($name) - 1) . '?';
	}
	/**
	* 与table 方法相同
	*/
	function from($table){
		$this->table($table);
		return $this;
	}
	//直接返回主键一行对象
	function pk($id){
		return $this->where('id=?',[$id])->one();
	}
  	/**
		$dsn = 'mysql:dbname=testdb;host=127.0.0.1';
		$user = 'dbuser';
		$password = 'dbpass'; 
	*/ 
	public function __construct($dsn,$user,$pwd){ 
		try {
			$this->dsn = $dsn;
			$this->user = $user;
			$this->pwd = $pwd;
		    $this->pdo = new \PDO($dsn, $user, $pwd,[
		    	\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'',
		    	\PDO::ATTR_CASE => \PDO::CASE_LOWER,
		     	\PDO::ATTR_DEFAULT_FETCH_MODE =>  \PDO::FETCH_OBJ,
		    ]); 
		    $this->active = true;
		    $this->connect = [
		    	'dsn'=>$dsn,
		    	'user'=>$user,
		    	'pwd'=>$pwd,
		    	'active'=>$active, 
		    ];
		} catch (\PDOException $e) {  
			$this->active = false;
		    return false;
		} 
	} 
	
	//数据库 从库
	static function r(){
		if(!isset(static::$read)){
			$db = Config::load('database');  
			if(count($db)>1){
				unset($db[0]);
				$i = array_rand ($db , 1);
				$config = $db[$i];
			}else{
				$config = $db[0];
			}
			static::$read = new Static($config[0],$config[1],$config[2]); 
		}
		return static::$read;
	}
	
	static function w(){
		if(!isset(static::$write)){
			$db = Config::load('database'); 
			$config = $db[0];
			static::$write = new Static($config[0],$config[1],$config[2]);  
		}
		return static::$write;
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
	 将 ['username'=>'admin','email'=>'test@msn.com'] 
	 转换成 "username = ? ,email = ? " ,['admin' , 'test@msn.com']
	*/
	protected function _to_sql($arr){
		foreach($arr as $k=>$v){
			$key[] = "`".$k."`=? "; 
		} 
		$key = implode(",",$key);  
		$value = array_values($arr); 
		$this->key = $key;
		$this->value = $value;
	}
	/**
	 	insert('user',['username'=>'admin','email'=>'test@msn.com'])
	*/
	function insert($table,$arr = []){ 
		$this->_to_sql($arr);
		$this->sql = "INSERT INTO $table SET ".$this->key; 
		return $this->exec(true);
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
 		if($this->ar){
			foreach($this->ar AS $key=>$condition){
				if(strpos($key,'WHERE')!==false){
					$sql .= " WHERE 1=1 "; 
				}
				if(is_array($condition)){
					foreach($condition as $str=>$vo){
				 		$sql .= " ". $k." ".$str." "; 
				 	 	$value = array_merge($value,$vo);
				 	}
				}else{
					$sql .= " ".$key." ". $condition." ";
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