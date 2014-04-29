<?php

class AllModelTests extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite = new PHPUnit_Framework_TestSuite('All Model Tests');

		$path = APP_TEST_CASES . DS;

		$suite->addTestFile($path . 'Model/CollaboratorTest.php');
		$suite->addTestFile($path . 'Model/EmailConfirmationKeyTest.php');
		$suite->addTestFile($path . 'Model/LostPasswordKeyTest.php');
		$suite->addTestFile($path . 'Model/ProjectTest.php');
		//$suite->addTestFile($path . 'Model/RepoTypeTest.php');
		$suite->addTestFile($path . 'Model/SettingTest.php');
		//$suite->addTestFile($path . 'Model/SourceTest.php');
		$suite->addTestFile($path . 'Model/SshKeyTest.php');
		$suite->addTestFile($path . 'Model/TimeTest.php');
		$suite->addTestFile($path . 'Model/UserTest.php');
		return $suite;
	}
}
