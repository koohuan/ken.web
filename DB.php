<?php
/** 
	pdo 操作，MYSQL经过测试
	数据库设置，第一个为主库
	config/database.php
	
	return [
		'w'=>["mysql:dbname=api;host=127.0.0.1","test","test"],
		'r'=>[
			["mysql:dbname=wei2;host=127.0.0.1","test","test"],
		],
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
 	$db->debug = true;
	$r = $db->table('posts')
		->select('a.id,a.name')
		->left_join('aa as b')
		->on('b.id=a.id')
		->where('a.name=?',['abc']) 
		->or_where('a.name=?',['abc'])
		->limit(10)
		->offset(1)
		->order_by('a.id asc')
		->all(); 
		
		select a.id ,a.name from posts
		left join aa as b 
		on b.id = a.id
		where a.name=?
		or a.name = ?
		limit 10
		offset 1
		order by a.id asc
		
	dump($db->log());
		
	$r = $db->table('posts')
		->where('name=?',['abc'])  
		->one();  
	分页[该方法不支持cache()]
	$db->page($url ,$per_page = 10 ,$count = "count(*) num")
	
	IN 操作
	$in = [1,2];
	\DB::w()->from('files')->where('id in ('.\DB::in($in).')',$in)->all(); 
	按值排序
	\DB::w()->from('files')->where('id in ('.\DB::in($in).')',$in)->order_by("FIELD ( id ,".implode(',' , $in).") ")->all(); 
	
	insert_batch('user',[
	 		['username'=>'admin','email'=>'test@msn.com'],
	 		['username'=>'admin','email'=>'test@msn.com'],
	 	])
	如果要避免重复，需要设置唯一索引 	
 	DB::w()->load_file('test',public_path().'/1.csv',[
		'body'
	]); 
	
	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014
*/
namespace Ken\Web;
 
class DB{ 
	public $pdo; 
	public $query;
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
	public $cache = false;
	public $key_batch;
	//主从
	static $read;
	static $write;
	//where 条件 
	static $_set_where;
	static $_cache_key;
	/**
	* 分页
	*/
	function page($url ,$per_page = 10 ,$count = "count(*) num"){ 
		$this->count($count); 
		$this->_query(true);  
		$row = $this->query->fetch(\PDO::FETCH_OBJ);  
		$paginate = new Paginate($row->num,$per_page); 
		$paginate->url = $url;
		$limit = $paginate->limit;
		$offset = $paginate->offset;  
		//显示分页条，直接输出 $paginate
		$pages = $paginate->show();    
	 	$this->limit($limit);
		$this->offset($offset);  
		$this->_query(true);
		$posts = $this->query->fetchAll();
		unset($this->ar,$this->where);  
		return (object)[
			'posts'=>$posts,
			'pages'=>$pages
		];
	}
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
			$db = Config::load('database.r');   
			$i = array_rand ($db , 1);
			$config = $db[$i];
			if(!$config)
				$config = $db[0]; 
			static::$read = new Static($config[0],$config[1],$config[2]); 
		}
		static::$write[$default]->cache = false;
		return static::$read;
	}

	static function w($default='w'){
		if(!isset(static::$write[$default])){
			$config = Config::load('database.'.$default);  
			static::$write[$default] = new Static($config[0],$config[1],$config[2]);  
		} 
		static::$write[$default]->cache = false;
		return static::$write[$default];
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
	 	insert_batch('user',[
	 		['username'=>'admin','email'=>'test@msn.com'],
	 		['username'=>'admin','email'=>'test@msn.com'],
	 	])
	*/
	function insert_batch($table,$arr = []){ 
		$this->_to_sql_batch($arr);
		$this->sql = "INSERT INTO $table ($this->key) $this->key_batch";   
		return $this->exec(true);
	}  
	protected function _to_sql_batch($arrs){ 
		$set_value = false;
		unset($this->key_batch);
		foreach($arrs as $arr){
			unset($vo,$vs);
			foreach($arr as $k=>$v){
				$key[$k] = "`".$k."`";   
				$vo[] = "?";
				$value[] = $v; 
			}   
			if(false === $set_value ) $vs = "values";  
			$this->key_batch[] = "$vs(".implode(',',$vo).") ";
			$set_value = true;
		}
		$key = implode(",",$key);   
		$this->key = $key;
		$this->key_batch = implode(",",$this->key_batch);   
		$this->value = $value;  
	}
	/**
	DB::w()->load_file('test',public_path().'/1.csv',[
		'body'
	]); 
	如果要避免重复，需要设置唯一索引
	*/
	function load_file($table,$file,$data = [],$arr = [
		'FIELDS'=>',',
		'ENCLOSED'=>'\"',
		'LINES'=>'\r\n',
		'CHARACTER'=>"utf8",
		//'IGNORE'=>1,
	]){
		$file = str_replace('\\','/',$file);
		if($data){
			$filed = "(`".implode('`,',$data);
			$filed .="`)";
		}
		foreach($arr as $k=>$v){
			$arr[strtoupper($k)] = $v;
		}
		$this->sql = "LOAD DATA INFILE '".$file."' REPLACE INTO  TABLE ".$table."
		  CHARACTER SET ".$arr['CHARACTER']."
		  FIELDS TERMINATED BY '".$arr['TERMINATED']."' ENCLOSED BY '".$arr['ENCLOSED']."'
		  LINES TERMINATED BY '".$arr['LINES'] ."' ".$filed;
		if($arr['IGNORE'])
			$this->sql .= " IGNORE ".$arr['IGNORE']." LINES;"; 
		return $this->exec(true); 
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
			if(!is_array($value)) $value = [$value];
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
			if(!is_array($value)) $value = [$value];
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
		$this->cache_id = 'mysql_'.json_encode($this->ar).$this->sql.$this->cache_time; 
		$this->cache = true;
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
		if( true === $this->cache ){
			$id = "DB_".md5($this->cache_id.'one'); 
			static::$_cache_key[$id] = true;
			$value = Cache::get($id); 
			if(!$value){
				$value = $this->_one();
				if($value){
					Log::mo([
						'id'=>$id,
					],'db_cache_one');
					Cache::set($id,$value ,$this->cache_time);  
				}
			}else{
				unset($this->ar,$this->where);  
			}
		} else{
			$value = $this->_one();
		}  
		
		return $value; 
	}
	function cache_key(){
		return static::$_cache_key;
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
		if(true === $this->cache){
			$id = "DB_".md5($this->cache_id.'all'); 
			static::$_cache_key[$id] = true;
			$value = Cache::get($id); 
			if(!$value){  
				$value = $this->_all();   
				if($value){
					Log::mo([
						'id'=>$id,
					],'db_cache_all');
					Cache::set($id,$value , $this->cache_time);  
				}
			}else{
				unset($this->ar,$this->where);  
			}
		}else{
			$value = $this->_all(); 
		}
		if(sizeof($value)==0) return null;
		return $value;
	}
	/**
		支持纯SQL
	*/
	function sql($sql,$value=[]){
		$this->sql = $sql;
		$this->value = $value;   
		$this->exec(); 
		return $this;
	}

	/**
	* exec sql 
	*/
	protected function _query($keep_ar = false){  
		static::$_set_where = false;
		$value = [];
		if(!$this->ar['TABLE']) return $this;
		$sql = "select ".($this->ar['SELECT']?:'*')." FROM ".$this->ar['TABLE'];
		$s = $this->ar['SELECT'];
		$t = $this->ar['TABLE'];
		unset($this->ar['SELECT'],$this->ar['TABLE']); 
 		if($this->ar){
			foreach($this->ar AS $key=>$condition){
				if(strpos($key,'WHERE')!==false && static::$_set_where===false){
					$sql .= " WHERE 1=1  "; 
					static::$_set_where = true;
				}
				if(is_array($condition)){
					foreach($condition as $str=>$vo){
				 		$sql .= " ". $k." ".$str." "; 
				 	 	$value = array_merge($value,$vo);
				 	}
				}else{
					if(strpos($key,'WHERE')!==false)
						 $sql .= " ". $condition." ";
					else
						$sql .= " ".$key." ". $condition." ";
			 	}
			}
		}   
		//使用后要删除 $this->ar
		if(false === $keep_ar){
			unset($this->ar,$this->where); 
		}else{
			$this->ar['SELECT'] = "*";
			$this->ar['TABLE'] = $t;
		}
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
		try {  
			$this->query = $this->pdo->prepare($this->sql , [\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY]);  
	        $this->pdo->beginTransaction();  
	        if(!$this->query->execute( $this->value )){ 
	        	$info = $this->query->errorInfo()[2]; 
	        	$info = 'SQL ERROR:'.$info."<br>SQL:".$this->sql;
	        	\Log::mysql_error($info);
	        	\Response::code(500,'SQL ERROR');
	        	throw new \Exception($info,500); 
	        }
	        $id = $this->pdo->lastInsertId();
	     	$this->pdo->commit();   
	    } catch(PDOExecption $e) {   
 	        $this->pdo->rollback();  
	    }  
	    //记录日志
		if($this->debug === true){
			$log = ['sql'=>$this->sql,'value'=>$this->value];
			static::$log[] = $log;
		} 
		
	    return $id?:false; 
	}

	function __call ($name ,$arg = [] ){ 
		$name = strtoupper($name);
		$key = $arg[0];
		$vo = $arg[1];
		if($name=='WHERE') $name = "AND_WHERE";
		$name = str_replace('_',' ',$name);  
		if(strpos($name,'WHERE')!==false){  
			$arr = explode(' ',$name);
			$key = $arr[0]." ".$key;
			if(!is_array($vo)) $vo = [$vo];
		} 
		if($vo){
			$this->ar[$name][$key] = $vo;
		}else if($key){  
			$this->ar[$name] = $key;
		}

		return $this;
	} 


}