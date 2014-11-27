<?php

class AllModelTests extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite = new PHPUnit_Framework_TestSuite('All Model Tests');

		$path = APP_TEST_CASES . DS;

		$suite->addTestFile($path . 'Model/ApiKeyTest.php');
		$suite->addTestFile($path . 'Model/AttachmentTest.php');
		$suite->addTestFile($path . 'Model/CollaboratorTest.php');
		$suite->addTestFile($path . 'Model/EmailConfirmationKeyTest.php');
		$suite->addTestFile($path . 'Model/LostPasswordKeyTest.php');
		$suite->addTestFile($path . 'Model/MilestoneBurndownLogTest.php');
		$suite->addTestFile($path . 'Model/MilestoneTest.php');
		$suite->addTestFile($path . 'Model/ProjectBurndownLogTest.php');
		$suite->addTestFile($path . 'Model/ProjectHistoryTest.php');
		$suite->addTestFile($path . 'Model/ProjectTest.php');
		$suite->addTestFile($path . 'Model/RepoTypeTest.php');
		$suite->addTestFile($path . 'Model/SettingTest.php');
		$suite->addTestFile($path . 'Model/SourceTest.php');
		$suite->addTestFile($path . 'Model/SshKeyTest.php');
		$suite->addTestFile($path . 'Model/TaskCommentTest.php');
		$suite->addTestFile($path . 'Model/TaskPriorityTest.php');
		$suite->addTestFile($path . 'Model/TaskStatusTest.php');
		$suite->addTestFile($path . 'Model/TaskTest.php');
		$suite->addTestFile($path . 'Model/TaskTypeTest.php');
		$suite->addTestFile($path . 'Model/TeamTest.php');
		$suite->addTestFile($path . 'Model/TimeTest.php');
		$suite->addTestFile($path . 'Model/UserTest.php');
		return $suite;
	}
}
