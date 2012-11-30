<?php

	/****************************************************************/
	/* PancakeTest                                                  */
	/* TRACE.php                                          			*/
	/* 2012 Yussuf Khalil                                           */
	/****************************************************************/
	
	require_once 'Pancake/Pancake.php';
	
	require_once 'HTTP/Request2.php';
	require_once 'HTTP/Request2/Adapter/Socket.php';
	
	class TRACE extends PHPUnit_Framework_TestCase
	{
		public function testTRACE() {
			$this->assertTrue(Pancake::testConnection(array('sec' => 0, 'usec' => 1000)));
			
			$request = new HTTP_Request2('http://127.0.0.1:' . Pancake::getPort() . '/');
			$request->setMethod('TRACE');
			$request->setHeader('X-Test', 'Test');
			$request->setHeader('Host', 'testsuite.default');
			$request->setHeader('Accept-Encoding', "");
			$response = $request->send();
			
			$this->assertSame($response->getHeader('Content-Type'), 'message/http');
			
			$expect = "TRACE / HTTP/1.1\r\n";
			
			foreach($request->getHeaders() as $name => $value) {
				$expect .= $name . ": " . $value . "\r\n";
			}
			
			$expect .= "\r\n";
			
			$this->assertSame(strtolower($response->getBody()), strtolower($expect));
		}
	}

?>