WIDGET
========
 

    http://phpseclib.sourceforge.net/crypt/examples.html
    
    //默认是使用AES
    Crypt::type('AES');
    //请使用前一定要重新设置key
    Crypt::key('abcd');

    echo Crypt::encode('abc');
	
    echo Crypt::decode(value);