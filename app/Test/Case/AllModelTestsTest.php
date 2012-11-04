<?php

class AllModelTests extends PHPUnit_Framework_TestSuite {

    public static function suite() {
        $suite = new PHPUnit_Framework_TestSuite('All Model Tests');

        $path = APP_TEST_CASES . DS;

        $suite->addTestFile($path . 'Model/ProjectTest.php');
        $suite->addTestFile($path . 'Model/UserTest.php');
        $suite->addTestFile($path . 'Model/CollaboratorTest.php');
        $suite->addTestFile($path . 'Model/SettingTest.php');
        return $suite;
    }
}
