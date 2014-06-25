生成xls
========

	use Ken\Web\Vendor\Excel_XML;
	$xls = new Excel_XML;
	$xls->addArray ( $myarray );
	$xls->generateXML ( "testfile" );
	$xls->save ( "testfile.xls" );


	 $xml = Array2XML::createXML('root_node_name', $php_array);
	 echo $xml->saveXML();


	 $pinyin = new Pinyin('太极拳');
	 echo $pinyin->full(); // taijiquan
	 echo $pinyin->first(); // tjq


	Spyc::YAMLLoad($file) YAMLLoadString  YAMLDump dump

	$array = XML2Array::createArray($xml);

	XSS::xss_clean($str);