<?php
/**
   	Validate 
 	如果 使用 unique 将依赖 DB.php 
 	Validate::set('username',[
			['not_empty'],
			['between',3,5]
	]);
	
	完整事例
	控制器 
	
	Action 代码
	
	if($_POST){
		Validate::set('username',[
			['not_empty','message'=>'not empty'], 
		]);
		
		Validate::set('password',[
			['not_empty','message'=>'not empty'], 
		]); 
		if(Validate::run()){  
		 	
		} 
 	}
  	$vali = Validate::message();
 	if($vali) $error  = $vali[0];
 	$this->view('login',['error'=>$error]);
 	
 	视图
 	
 	<?php  
	 
	$this->layout('default'); 
	?>
		
	<?php echo $this->start('content');?>
		
		<?php echo Form::open('form',[
			'method'=>'POST'
		]);?>
		
		<?php if($error){?>
			<p class="bg-danger"><?php echo $error;?></p>
		<?php }?>
		 
		<?php echo Form::label('username');?> 
		<?php echo Form::input('username',['tag'=>'a']);?>
		
		<?php echo Form::label('password');?>
		<?php echo Form::password('password');?> 
	 
		<p>	
			<?php echo Form::submit('login');?>
		</p>	
		<?php echo Form::close();?>
			
	<?php echo $this->end();?>
	
	修改自 https://github.com/dualface/qeephp2_x/blob/master/qeephp2_1/library/helper/validator.php
	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014
*/
namespace Ken\Web;

class Validate{   
	static $par = [
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
	 
		
	];
	static $_errors = [];
	/**
		设置验证规则
		Validate::set('username',[
				['not_empty'],
				['between',3,5]
		]);
	*/
	public static function vali($value,$validate = []){
		return static::set($name,$validate,true);
	}
	public static function set($name,$validate = [],$_val = null){
		if(is_array($name)){
			foreach($name as $new_name){ 
				static::set($new_name,$validate);
			}
			return;
		} 
	  	if($_val)
	  		$value = $_val;
	  	else
	  		$value = trim($_POST[$name]); 
	  	foreach($validate as $v){
	  		$message = $v['message'];
	  		unset($v['message']);
	  		$a = $v[0];
	  		unset($v[0]);
	  		$v = array_merge([$value],$v);  
	  		if(!in_array($a,static::$par))  {
	  			static::$_errors[] = $name." validate method $a not support";
	  			continue;
	  		} 
	  		if(!call_user_func_array([__CLASS__,$a],$v)){
	  			static::$_errors[] = $message;
	  		} 
	  	}  
	} 
	/**
		取得错误信息
	*/
	static function message(){
		$rt = static::$_errors; 
	  	static::$_errors=[]; 
	  	return $rt;
	}
	/**
		执行验证
	*/
	static function run()
    {
        if(!static::$_errors) return true;
        return false; 
    }
	
	/**
      	验证唯一
     */
    static function unique($value, $table ,$name,$f='db')
    { 
    	 if(is_array($name)){
    	 	foreach($name as $k){
    	 		$im[] = $k.'=?';
    	 		$v[] = trim($_POST[$k]);
    	 	}
    	 	$value = $v;
    	 	$name = implode(' AND ',$im);
    	 }else{
    	 	$value = trim($_POST[$name]);
    	 }
         $one = F::get($f)->table($table)->where($name.'=?',[$value])
         	 ->one();
         if($one->id == (int)$_GET['id']) return true;
         return $one?false:true;
    }
	/**
      	最小长度 
     */
    static function min_length($value, $len)
    {
        return strlen($value) >= $len;
    }

    /**
       最大长度
     */
    static function max_length($value, $len)
    {
        return strlen($value) <= $len;
    }

    /**
     	最小值
     */
    static function min($value, $min)
    {
        return $value >= $min;
    }

    /**
    	最大值 
     */
    static function max($value, $max)
    {
        return $value <= $max;
    }

    /**
     	在两个值之间 
     */
    static function between($value, $min, $max, $inclusive = true)
    {
        if ($inclusive)
        {
            return $value >= $min && $value <= $max;
        }
        else
        {
            return $value > $min && $value < $max;
        }
    }

    /**
    	大于指定值 
     */
    static function greater_than($value, $test)
    {
        return $value > $test;
    }

    /**
     	大于等于指定值 
    */
    static function greater_or_equal($value, $test)
    {
        return $value >= $test;
    }

    /**
      小于指定值 
    */
    static function less_than($value, $test)
    {
        return $value < $test;
    }
    
     /**
      不为 null 
     */
    static function not_null($value)
    {
        return !is_null($value);
    }

    /**
     不为空
    */
    static function not_empty($value)
    {
        return strlen($value);
    }

     

    /**
       是否是字母加数字 
     */
    static function alnum($value)
    {
        return ctype_alnum($value);
    }

    /**
      是否是字母
     */
    static function alpha($value)
    {
        return ctype_alpha($value);
    }

    /**
        是否是字母、数字加下划线 
     */
    static function alnumu($value)
    {
        return preg_match('/[^a-zA-Z0-9_]/', $value) == 0;
    }

    

    /**
      是否是数字 
     */
    static function digits($value)
    {
        return ctype_digit($value);
    }

    

    /**
      是否是全小写
     */
    static function lower($value)
    {
        return ctype_lower($value);
    }

    
    /**
     是否是全大写
     */
    static function upper($value)
    {
        return ctype_upper($value);
    }

    /**
      是否是十六进制数 
     */
    static function xdigits($value)
    {
        return ctype_xdigit($value);
    }

    /**
      是否是 ASCII 字符 
     */
    static function ascii($value)
    {
        return preg_match('/[^\x20-\x7f]/', $value) == 0;
    }

    
	/**
       验证字符串长度 
     */
    static function strlen($value, $len)
    {
        return strlen($value) == (int)$len;
    }
	/**
		判断是否是中文
	*/
	public static function chinese($str){
	 	return preg_match("/[\x7f-\xff]/", $str);
	} 
	/**
		EMAIL
	*/
	public static function email($str){
	 	return filter_var($str, FILTER_VALIDATE_EMAIL);
	}  
	/**
		URL
	*/
	public static function url($str){
	 	return filter_var($str, FILTER_VALIDATE_URL);
	}  
	/**
		IP
	*/
	public static function ip($str){
	 	return filter_var($str, FILTER_VALIDATE_IP);
	} 
	/**
		手机
	*/
	public static function phone($input){
	     return !empty($input) && preg_match('/^[+]?([\d]{0,3})?[\(\.\-\s]?([\d]{1,3})[\)\.\-\s]*(([\d]{3})[\.\-\s]?([\d]{4})|([\d]{2}[\.\-\s]?){4})$/', $input);
	}
	/**
		整型
	*/
	public static function int($str){
	 	return filter_var($str, FILTER_VALIDATE_INT)?true:false;
	}  
	/**
		float 型
	*/
	public static function float($str){
	 	return filter_var($str, FILTER_VALIDATE_FLOAT)?true:false;
	}
	
	/**
		正则
	*/
	public static function preg($value, $regxp){ 
        return preg_match($regxp, $value) > 0;
	} 
	/**
		2个值相同
	*/
	static function equal($value, $test)
    {
        return $value == $test && strlen($value) == strlen($test);
    }
    
    /**
       是否是 IPv4 地址（格式为 a.b.c.h） 
     */
    static function ipv4($value)
    {
        $test = @ip2long($value);
        return $test !== - 1 && $test !== false;
    }

    /**
       是否是八进制数值 
     */
    static function octal($value)
    {
        return preg_match('/0[0-7]+/', $value);
    }

    /**
      是否是二进制数值 
     */
    static function binary($value)
    {
        return preg_match('/[01]+/', $value);
    }

    /**
      是否是 Internet 域名 
     */
    static function domain($value)
    {
        $regular = "/^([0-9a-z\-]{1,}\.)?[0-9a-z\-]{2,}\.([0-9a-z\-]{2,}\.)?[a-z]{2,}$/i";
        return preg_match($regular, $value);
    }
    
    /**
     	是否是日期（yyyy/mm/dd、yyyy-mm-dd）
     */
    static function date($value)
    {
        if (strpos($value, '-') !== false)
        {
            $p = '-';
        }
        elseif (strpos($value, '/') !== false)
        {
            $p = '\/';
        }
        else
        {
            return false;
        }

        if (preg_match('/^\d{4}' . $p . '\d{1,2}' . $p . '\d{1,2}$/', $value))
        {
            $arr = explode($p, $value);
            if (count($arr) < 3) return false;

            list($year, $month, $day) = $arr;
            return checkdate($month, $day, $year);
        }
        else
        {
            return false;
        }
    }

    /**
      是否是时间（hh:mm:ss） 
     */
    static function time($value)
    {
        $parts = explode(':', $value);
        $count = count($parts);

        if ($count != 2 && $count != 3)
        {
            return false;
        }

        if ($count == 2)
        {
            $parts[2] = '00';
        }

        $test = @strtotime($parts[0] . ':' . $parts[1] . ':' . $parts[2]);

        if ($test === - 1 || $test === false || date('H:i:s', $test) != implode(':', $parts))
        {
            return false;
        }

        return true;
    }

    /**
       是否是日期 + 时间 
    */
    static function datetime($value)
    {
        $test = @strtotime($value);
        if ($test === false || $test === - 1)
        {
            return false;
        }
        return true;
    }
	
	 
	
}