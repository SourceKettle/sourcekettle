<?php

class AllModelTests extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite = new CakeTestSuite('All Model Tests');

		$path = APP_TEST_CASES . DS;

		$suite->addTestDirectoryRecursive($path . 'Model' . DS);
		return $suite;
	}
}
