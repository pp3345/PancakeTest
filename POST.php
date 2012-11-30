<?php

	/****************************************************************/
	/* PancakeTest                                                  */
	/* POST.php                                          			*/
	/* 2012 Yussuf Khalil                                           */
	/****************************************************************/
	
	require_once 'Pancake/Pancake.php';
	
	require_once 'HTTP/Request2.php';
	require_once 'HTTP/Request2/MultipartBody.php';
	
	class POST extends PHPUnit_Framework_TestCase
	{
		public function testSimplePOST() {
			$this->assertTrue(Pancake::testConnection(array('sec' => 0, 'usec' => 1000)));
			
			$request = new HTTP_Request2('http://127.0.0.1:' . Pancake::getPort() . '/POST.php');
			$request->setHeader('Host', 'testsuite.default');
			$request->setHeader('Content-Type', 'application/x-www-form-urlencoded');
			$request->setMethod('POST');
			$request->addPostParameter('a', 'b');
			
			$adapter = new HTTP_Request2_Adapter_Socket;
			$response = $adapter->sendRequest($request);
			
			$expect = array('a' => 'b');
			
			$this->assertSame(unserialize($response->getBody()), $expect);
		}
		
		public function testPOSTArrays() {
			$this->assertTrue(Pancake::testConnection(array('sec' => 0, 'usec' => 1000)));
			
			$request = new HTTP_Request2('http://127.0.0.1:' . Pancake::getPort() . '/POST.php');
			$request->setHeader('Host', 'testsuite.default');
			$request->setHeader('Content-Type', 'application/x-www-form-urlencoded');
			$request->setMethod('POST');
			$request->setBody('a[]=7&a[]=8&a[abc]=xyz&b=eee&n[a][b][c]=1&n[a][b][d]=2&n[a][c][d]=3');
			
			$adapter = new HTTP_Request2_Adapter_Socket;
			$response = $adapter->sendRequest($request);
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
		
		public function testPOSTMultipartData() {
			$this->assertTrue(Pancake::testConnection(array('sec' => 0, 'usec' => 1000)));
				
			$request = new HTTP_Request2('http://127.0.0.1:' . Pancake::getPort() . '/POST.php');
			$request->setHeader('Host', 'testsuite.default');
			$request->setHeader('Content-Type', 'multipart/form-data');
			$request->setMethod('POST');
			
			$params = array (
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
			
			$body = new HTTP_Request2_MultipartBody($params, array());
			
			$request->setBody($body);
				
			$adapter = new HTTP_Request2_Adapter_Socket;
			$response = $adapter->sendRequest($request);
			
			$this->assertSame(unserialize($response->getBody()), $params);
		}
		
		public function testPOSTUploads() {
			$this->assertTrue(Pancake::testConnection(array('sec' => 0, 'usec' => 1000)));
			
			chdir(dirname(__FILE__));
			
			$request = new HTTP_Request2('http://127.0.0.1:' . Pancake::getPort() . '/POST_FILES.php');
			$request->setHeader('Host', 'testsuite.default');
			$request->setHeader('Content-Type', 'multipart/form-data');
			$request->setMethod('POST');
			$request->addUpload('testFileUpload', 'uploadTest.txt', 'uploadTest.txt', 'text/plain');
			$response = $request->send(); 
			
			$expect = array (
					  'testFileUpload' => 
					  array (
					    'name' => 'uploadTest.txt',
					    'type' => 'text/plain',
					    'error' => 0,
					    'size' => 10,
					  ),
					);
			
			$result = unserialize($response->getBody());
			
			$this->assertArrayHasKey('testFileUpload', $result);
			$this->assertArrayHasKey('tmp_name', $result['testFileUpload']);
			
			unset($result['testFileUpload']['tmp_name']);
			
			$this->assertSame($result, $expect);
		}
	}

?>
