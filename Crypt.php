<?php 
/** 
 	 
    http://phpseclib.sourceforge.net/crypt/examples.html
    
    ����
    config/app.php
    
    crypt_key Ĭ�� abc
    crypt_type Ĭ�� AES
    
  
	echo Crypt::encode('abc');
	
    echo Crypt::decode(value);
    
    
 	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
 */
namespace Ken\Web;
class Crypt extends \Ken\Web\Vendor\PhpsecLib{}
 