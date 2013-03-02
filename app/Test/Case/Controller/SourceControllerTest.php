<?php
App::uses('SourceController', 'Controller');

/**
 * TestSourceController *
 */
class TestSourceController extends SourceController {
/**
 * Auto render
 *
 * @var boolean
 */
	public $autoRender = false;

/**
 * Redirect action
 *
 * @param mixed $url
 * @param mixed $status
 * @param boolean $exit
 * @return void
 */
	public function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

/**
 * SourceController Test Case
 *
 */
class SourceControllerTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array();

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Source = new TestSourceController();
		$this->Source->constructClasses();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Source);

		parent::tearDown();
	}

/**
 * testAjaxDiff method
 *
 * @return void
 */
	public function testAjaxDiff() {

	}
/**
 * testCommit method
 *
 * @return void
 */
	public function testCommit() {

	}
/**
 * testCommits method
 *
 * @return void
 */
	public function testCommits() {

	}
/**
 * testGettingStarted method
 *
 * @return void
 */
	public function testGettingStarted() {

	}
/**
 * testIndex method
 *
 * @return void
 */
	public function testIndex() {

	}
/**
 * testRaw method
 *
 * @return void
 */
	public function testRaw() {

	}
/**
 * testTree method
 *
 * @return void
 */
	public function testTree() {

	}
}
