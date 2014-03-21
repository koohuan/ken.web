<?php  
/** 
	 
 	 
	@auth Kang Sun <68103403@qq.com>
	@license BSD
	@date 2014 
*/
namespace Ken\Web; 
 
class Helper
{ 
	/**
		http://php.net/manual/en/function.http-parse-headers.php
	
	*/
	static function http_parse_headers($raw_headers)
    {
        $headers = array();
        $key = ''; // [+]

        foreach(explode("\n", $raw_headers) as $i => $h)
        {
            $h = explode(':', $h, 2);

            if (isset($h[1]))
            {
                if (!isset($headers[$h[0]]))
                    $headers[$h[0]] = trim($h[1]);
                elseif (is_array($headers[$h[0]]))
                {
                    // $tmp = array_merge($headers[$h[0]], array(trim($h[1]))); // [-]
                    // $headers[$h[0]] = $tmp; // [-]
                    $headers[$h[0]] = array_merge($headers[$h[0]], array(trim($h[1]))); // [+]
                }
                else
                {
                    // $tmp = array_merge(array($headers[$h[0]]), array(trim($h[1]))); // [-]
                    // $headers[$h[0]] = $tmp; // [-]
                    $headers[$h[0]] = array_merge(array($headers[$h[0]]), array(trim($h[1]))); // [+]
                }

                $key = $h[0]; // [+]
            }
            else // [+]
            { // [+]
                if (substr($h[0], 0, 1) == "\t") // [+]
                    $headers[$key] .= "\r\n\t".trim($h[0]); // [+]
                elseif (!$key) // [+]
                    $headers[0] = trim($h[0]);trim($h[0]); // [+]
            } // [+]
        }

        return $headers;
    }
    /**
    	http://www.php.net/manual/en/function.http-parse-cookie.php
    	
    */
   	static function cookie_parse( $header ) {
	        $cookies = array();
	        foreach( $header as $line ) {
	                if( preg_match( '/^Set-Cookie: /i', $line ) ) {
	                        $line = preg_replace( '/^Set-Cookie: /i', '', trim( $line ) );
	                        $csplit = explode( ';', $line );
	                        $cdata = array();
	                        foreach( $csplit as $data ) {
	                                $cinfo = explode( '=', $data );
	                                $cinfo[0] = trim( $cinfo[0] );
	                                if( $cinfo[0] == 'expires' ) $cinfo[1] = strtotime( $cinfo[1] );
	                                if( $cinfo[0] == 'secure' ) $cinfo[1] = "true";
	                                if( in_array( $cinfo[0], array( 'domain', 'expires', 'path', 'secure', 'comment' ) ) ) {
	                                        $cdata[trim( $cinfo[0] )] = $cinfo[1];
	                                }
	                                else {
	                                        $cdata['value']['key'] = $cinfo[0];
	                                        $cdata['value']['value'] = $cinfo[1];
	                                }
	                        }
	                        $cookies[] = $cdata;
	                }
	        }
	        return $cookies;
	}
   
}