<?php  
/**
 	Json  
    
    来源 https://github.com/yiisoft/yii/blob/master/framework/web/helpers/CJavaScript.php
     
	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
*/
namespace Ken\Web; 
use Ken\Web\Vendor\CJavaScript;
class Json
{ 
   
   
    
    public static function encode($value)
    {
     
     	return CJavaScript::encode($value);
       
    }

    
   
   
}