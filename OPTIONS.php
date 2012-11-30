<?php

	/****************************************************************/
	/* PancakeTest                                                  */
	/* OPTIONS.php                                          		*/
	/* 2012 Yussuf Khalil                                           */
	/****************************************************************/
	
	require_once 'Pancake/Pancake.php';
	
	require_once 'HTTP/Request2.php';
	
	class OPTIONS extends PHPUnit_Framework_TestCase
	{
		public function testOPTIONS() {
			$this->assertTrue(Pancake::testConnection(array('sec' => 0, 'usec' => 1000)));
			
			$request = new HTTP_Request2('http://127.0.0.1:' . Pancake::getPort() . '/SimpleGET.txt');
			$request->setMethod('OPTIONS');
			$response = $request->send();
			
			$this->assertSame($response->getHeader('Allow'), 'GET, POST, OPTIONS, HEAD, TRACE');
			$this->assertSame($response->getBody(), "SimpleGET");
		}
	}

?>