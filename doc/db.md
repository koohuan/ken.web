数据库操作
======== 

数据库配置 `config/database.php`
	
	return  [
	
		'w'=>["mysql:dbname=test;host=127.0.0.1","test","test"],
		'r'=>[
			["mysql:dbname=test_read;host=127.0.0.1","test","test"],
		],	 
	];


连接数据库

	DB::w(); 主库
	DB::r(); 从库

查寻所有数据
	
	方式一

	\DB::w()->table('pay_order')->all(); 

	方式二

	while($r =  \DB::w()->table('pay_order')->query->fetch()){
	 
	}

写数据
	
 	echo DB::w()->insert('posts',['name'=>'test']);

导入文件 
如果要避免重复，需要设置唯一索引

	DB::w()->load_file('test',public_path().'/1.csv',[
		'body'
	]);
	
类中参数为

	load_file($table_name,$file_dir,$colums = [],$arr = [
		'FIELDS'=>',',
		'ENCLOSED'=>'\"',
		'LINES'=>'\r\n',
		'CHARACTER'=>"utf8",
		//'IGNORE'=>1,
	])

批量写数据
	$n = 1000;
	for($i=0;$i<$n;$i++){
		$v[] = ['title'=>'admin'.$i,'body'=>'body'.$i];
	}
	DB::w()->insert_batch('test',$v);

更新记录
 	
 	DB::w()->update('posts',['name'=>'abcaaa'],'id=?',[1]);

删除数据
	
 	DB::w()->delete('posts','id=?',[1]);


查寻数据
支持mysql 数据库。如order by ,请在 `DB::w()->table('table')->order_by('id desc');`
其他方法依次类推
	
	$r = DB::w()->table('posts')
		->where('name=?',['abc']) 
		->order_by('id asc')
		->all(); 
		
	$r = $db->table('posts')
		->where('name=?',['abc'])  
		->one();  

数据库调试
	
	//打开调试
	DB::w()->debug=true;
	
	//输出SQL日志
	dump(DB::w()->log());

 