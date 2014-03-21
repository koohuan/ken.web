<?php 
/** 
 	可使用F注册
    Crypt 加密解密
    RIJNDAEL_128 加密
	
	F::set('crypt',function() use ($crypt){ 
		return new Crypt('abc');  
	});

	$s = F::get('crypt')->set('你们');
 	echo $s.'<br>';
 	echo F::get('crypt')->get($s).'<br>';


    
 	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
 */
namespace Ken\Web;
class Crypt
{  
	public $vi_size;
	public $key;
	function __construct($key){  
		$key = pack('H*', md5($key));
		$this->key = $key;
	    $key_size =  strlen($key);
	    $this->iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
 	}
	/**
		encode
	*/
	function set($plaintext){    
	    $iv = mcrypt_create_iv($this->iv_size, MCRYPT_RAND); 
	    $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->key,
	                                 $plaintext, MCRYPT_MODE_CBC, $iv); 
	    $ciphertext = $iv . $ciphertext;
	    return base64_encode($ciphertext);
	}
	/**
		decode
	*/
	function get($ciphertext_base64){  
		$ciphertext_dec = base64_decode($ciphertext_base64);
	    $iv_dec = substr($ciphertext_dec, 0, $this->iv_size);
 	    $ciphertext_dec = substr($ciphertext_dec, $this->iv_size);
 	    return @mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->key,
	                                    $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);
	}
	
 

	
 
}