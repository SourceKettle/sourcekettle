<?php

class AllTests extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite = new PHPUnit_Framework_TestSuite('All Tests');

		$path = APP_TEST_CASES . DS;

		$suite->addTestFile($path . 'AllModelTestsTest.php');
		$suite->addTestFile($path . 'AllControllerTestsTest.php');
		$suite->addTestFile($path . 'AllConsoleTestsTest.php');
		return $suite;
	}
}
