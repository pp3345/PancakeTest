<?php

	/****************************************************************/
	/* PancakeTest                                                  */
	/* Pancake.php													*/
	/* 2012 Yussuf Khalil                                           */
	/****************************************************************/

	class Pancake {
		const PORT = 200;
		
		public static function testConnection($timeout = array('sec' => 10)) {
			$socket = socket_create(\AF_INET, \SOCK_STREAM, \SOL_TCP);
			socket_set_option($socket, \SOL_SOCKET, \SO_SNDTIMEO, $timeout);
			$success = socket_connect($socket, '127.0.0.1', self::PORT);
			socket_close($socket);
			return $success;
		}
		
		public static function getPort() {
			return self::PORT;
		}
	}
?>