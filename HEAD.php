<?php

	/****************************************************************/
	/* PancakeTest                                                  */
	/* HEAD.php                                          			*/
	/* 2012 Yussuf Khalil                                           */
	/****************************************************************/
	
	require_once 'Pancake/Pancake.php';
	
	require_once 'HTTP/Request2.php';
	
	class HEAD extends PHPUnit_Framework_TestCase
	{
		public function testHEAD() {
			$this->assertTrue(Pancake::testConnection(array('sec' => 0, 'usec' => 1000)));
			
			$request = new HTTP_Request2('http://127.0.0.1:' . Pancake::getPort() . '/SimpleGET.txt');
			$request->setMethod('HEAD');
			$request->setHeader('Host', 'testsuite.default');
			$response = $request->send();
			
			$this->assertSame($response->getBody(), "");
			$this->assertEquals($response->getHeader('Content-Length'), 9);
			$this->assertSame($response->getHeader('Content-Type'), 'text/plain');
		}
	}

?>