<?php

class AllTests extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite = new CakeTestSuite('All Tests');

		$path = APP_TEST_CASES . DS;

		$suite->addTestFile($path . 'AllConsoleTestsTest.php');
		$suite->addTestFile($path . 'AllModelTestsTest.php');
		return $suite;
	}
}
