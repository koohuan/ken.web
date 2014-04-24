数据库操作
========
在php.app项目中直接使用 `F::get('db')` 代替 `$db` 返回主库  


连接数据库
	
 	$db = DB::w(); 主库
	$db = DB::r(); 从库
查寻

	\DB::w()->table('pay_order')->all(); 

	while($r =  \DB::w()->table('pay_order')->query->fetch()){
	 
	}

写数据
	
 	echo $db->insert('posts',['name'=>'test']);

更新记录
 	
 	$db->update('posts',['name'=>'abcaaa'],'id=?',[1]);

删除数据
	
 	$db->delete('posts','id=?',[1]);

查寻数据
支持mysql 数据库。如order by ,请在 $db->table('table')->order_by('id desc');
其他方法依次类推
	
	$r = $db->table('posts')
		->where('name=?',['abc']) 
		->order_by('id asc')
		->all(); 
		
	$r = $db->table('posts')
		->where('name=?',['abc'])  
		->one();  

数据库调试
	
	//打开调试
	$db->debug=true;
	
	//输出SQL日志
	dump($db->log());

$connect 属性 
		
	  [
	    	'dsn'=>$dsn,
	    	'user'=>$user,
	    	'pwd'=>$pwd,
	    	'active'=>$active, 
	    ];