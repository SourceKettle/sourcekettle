<?php

class AllControllerTests extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite = new PHPUnit_Framework_TestSuite('All Controller Tests');

		$path = APP_TEST_CASES . DS;

		$suite->addTestFile($path . 'Controller/CollaboratorsControllerTest.php');
		$suite->addTestFile($path . 'Controller/ProjectsControllerTest.php');
		$suite->addTestFile($path . 'Controller/SettingsControllerTest.php');
		$suite->addTestFile($path . 'Controller/SourceControllerTest.php');
		$suite->addTestFile($path . 'Controller/TimesControllerTest.php');
		$suite->addTestFile($path . 'Controller/MilestonesControllerTest.php');

		return $suite;
	}
}
