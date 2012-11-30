<?php

	/****************************************************************/
	/* PancakeTest                                                  */
	/* GET.php                                          			*/
	/* 2012 Yussuf Khalil                                           */
	/****************************************************************/
	
	require_once 'Pancake/Pancake.php';
	
	require_once 'HTTP/Request2.php';
	
	class GET extends PHPUnit_Framework_TestCase
	{
		public function testSimpleGET() {
			$this->assertTrue(Pancake::testConnection(array('sec' => 0, 'usec' => 1000)));
			
			$request = new HTTP_Request2('http://127.0.0.1:' . Pancake::getPort() . '/SimpleGET.txt');
			$request->setHeader('Host', 'testsuite.default');
			
			$adapter = new HTTP_Request2_Adapter_Socket;
			$response = $adapter->sendRequest($request);
			
			$this->assertSame($response->getBody(), 'SimpleGET');
		}
		
		public function testSimpleGETParameters() {
			$this->assertTrue(Pancake::testConnection(array('sec' => 0, 'usec' => 1000)));
				
			$request = new HTTP_Request2('http://127.0.0.1:' . Pancake::getPort() . '/GET.php?a=b');
			$request->setHeader('Host', 'testsuite.default');
				
			$adapter = new HTTP_Request2_Adapter_Socket;
			$response = $adapter->sendRequest($request);
			
			$expect = array('a' => 'b');
			
			$this->assertSame(unserialize($response->getBody()), $expect);
		}
		
		public function testComplexGETParameters() {
			$this->assertTrue(Pancake::testConnection(array('sec' => 0, 'usec' => 1000)));
			
			$request = new HTTP_Request2('http://127.0.0.1:' . Pancake::getPort() . '/GET.php?a[]=7&a[]=8&a[abc]=xyz&b=eee&n[a][b][c]=1&n[a][b][d]=2&n[a][c][d]=3');
			$request->setHeader('Host', 'testsuite.default');
			$response = $request->send();
				
			$expect = array (
					  'a' => 
					  array (
					    0 => '7',
					    1 => '8',
					    'abc' => 'xyz',
					  ),
					  'b' => 'eee',
					  'n' => 
					  array (
					    'a' => 
					    array (
					      'b' => 
					      array (
					        'c' => '1',
					        'd' => '2',
					      ),
					      'c' => 
					      array (
					        'd' => '3',
					      ),
					    ),
					  ),
					);
				
			$this->assertSame(unserialize($response->getBody()), $expect);
		}
	}

?>
