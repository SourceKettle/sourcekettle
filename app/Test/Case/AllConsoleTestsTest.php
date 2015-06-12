<?php

class AllConsoleTests extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite = new PHPUnit_Framework_TestSuite('All Console Tests');

		$path = APP_TEST_CASES . DS;

		$suite->addTestFile($path . 'Console/MaintenanceShellTest.php');
		return $suite;
	}
}
