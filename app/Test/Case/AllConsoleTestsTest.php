<?php

class AllConsoleTests extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite = new CakeTestSuite('All Console Tests');

		$path = APP_TEST_CASES . DS;

		$suite->addTestDirectoryRecursive($path . 'Console' . DS);
		return $suite;
	}
}
