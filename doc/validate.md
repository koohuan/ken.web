验证
========
src/Validate.php

设置字段`username` 不为空，且值在3到5之间

	Validate::set('username',[
			['not_empty'],
			['between',3,5]
	]);

完整事例

	Validate::set('username',[
		['not_empty','message'=>'not empty'], 
	]);
	Validate::set('password',[
		['not_empty','message'=>'not empty'], 
	]); 
	//验证成功
	if(Validate::run()){  
		
	}
	//取得验证错误信息
	$vali = Validate::message();
 	if($vali) $error  = $vali[0];
	//显示视图
 	$this->view('login',['error'=>$error]);

所有验证规则

	'unique',     // [unique,users,username,db] 其中db为F::get('db') 默认为db
	'min_length', // 最小长度 [min_length,2]
	'max_length', // 最大长度 [max_length,2]
	'min',	 	  // 最小值 [min,50]
	'max',		  // 最大值   [max,50]
	'between',    // 在两个值之间 	[between,5,20]
	'greater_than',// 大于指定值	[greater_than,50]
	'greater_or_equal',//大于等于指定值 [greater_or_equal,50]
	'less_than',// 小于指定值		[less_than,10]
	'not_empty',// 不为空	[not_empty]
	'not_null',// 不为NULL  [not_null]
	'alnum',// 字母加数字	[alnum]
	'alpha',// 字母		[alpha]
	'alnumu',// 字母数字下划线 [alnumu]
	'digits',// 数字	[digits]
	'lower',// 全小写	[lower]
	'upper',//全大写		[upper]
	'xdigits',//十六进制数 [xdigits]
	'ascii',//ASCII 字符 [ascii]
	'strlen',//字符串长度全等 [strlen,5]
	'chinese',//否是中文  [chinese]
	'email',//邮件地址	[email]
	'url', //URl地址	[url]
	'ip',  //IP地址		[ip]
	'phone', //手机号	[phone]
	'preg',  //正则		[preg,/[0-9]/]
	'equal', //2个值相同 [equal,123456]
	'ipv4', //IPv4 地址（格式为 a.b.c.h） [ipv4]
	'octal',//八进制数值 [octal]
	'binary',//二进制数值 [binary]
	'domain',//域名	[domain]
	'date',//日期（yyyy/mm/dd、yyyy-mm-dd）  [date]
	'time',//否是时间（hh:mm:ss）   [time]
	'datetime',//日期 + 时间   [datetime]
	