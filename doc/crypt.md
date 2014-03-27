WIDGET
========
 

    http://phpseclib.sourceforge.net/crypt/examples.html
    
    //默认是使用AES
    Crypt::type('AES');
    //请使用前一定要重新设置key
    Crypt::key('abcd');

    echo Crypt::encode('abc');
	
    echo Crypt::decode($value);

如果是数组

	Crypt::encode(serialize['abc']);

	unserialize(Crypt::decode($value));