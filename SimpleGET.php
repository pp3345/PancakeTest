<?php

	/****************************************************************/
	/* PancakeTest                                                  */
	/* SimpleGET.php                                          		*/
	/* 2012 Yussuf Khalil                                           */
	/****************************************************************/
	
	require_once 'Pancake/Pancake.php';
	
	require_once 'HTTP/Request2.php';
	require_once 'HTTP/Request2/Adapter/Socket.php';
	
	class SimpleGET extends PHPUnit_Framework_TestCase
	{
		public function test() {
			$this->assertTrue(Pancake::testConnection(array('sec' => 0, 'usec' => 1000)));
			
			$request = new HTTP_Request2('http://127.0.0.1:' . Pancake::getPort() . '/SimpleGET.txt');
			$request->setHeader('Host', 'testsuite.default');
			
			$adapter = new HTTP_Request2_Adapter_Socket;
			$response = $adapter->sendRequest($request);
			
			$this->assertSame($response->getBody(), 'SimpleGET');
		}
	}

?>
