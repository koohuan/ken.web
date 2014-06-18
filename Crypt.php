<?php 
/** 
 	 
    http://phpseclib.sourceforge.net/crypt/examples.html
    
    ÉèÖÃ
    config/app.php
    
    crypt_key Ä¬ÈÏ abc
    crypt_type Ä¬ÈÏ AES
    
  
	echo Crypt::encode('abc');
	
    echo Crypt::decode(value);
    
    
 	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
 */
namespace Ken\Web;
class Crypt extends \Ken\Web\Vendor\PhpsecLib{}
 