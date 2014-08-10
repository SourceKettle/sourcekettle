<?php

class AllControllerTests extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite = new PHPUnit_Framework_TestSuite('All Controller Tests');

		$path = APP_TEST_CASES . DS;

		$suite->addTestFile($path . 'Controller/AdminControllerTest.php');
		$suite->addTestFile($path . 'Controller/AttachmentsControllerTest.php');
		$suite->addTestFile($path . 'Controller/CollaboratorsControllerTest.php');
		$suite->addTestFile($path . 'Controller/DashboardControllerTest.php');
		$suite->addTestFile($path . 'Controller/MilestonesControllerTest.php');
		$suite->addTestFile($path . 'Controller/PagesControllerTest.php');
		$suite->addTestFile($path . 'Controller/ProjectsControllerTest.php');
		$suite->addTestFile($path . 'Controller/SettingsControllerTest.php');
		$suite->addTestFile($path . 'Controller/SourceControllerTest.php');
		$suite->addTestFile($path . 'Controller/SshKeysControllerTest.php');
		$suite->addTestFile($path . 'Controller/TasksControllerTest.php');
		$suite->addTestFile($path . 'Controller/TimesControllerTest.php');
		$suite->addTestFile($path . 'Controller/UsersControllerTest.php');
		$suite->addTestFile($path . 'Controller/VersionControllerTest.php');

		return $suite;
	}
}
