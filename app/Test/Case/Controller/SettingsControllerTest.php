<?php
App::uses('SettingsController', 'Controller');

/**
 * TestSettingsController *
 */
class TestSettingsController extends SettingsController {

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
 * SettingsController Test Case
 *
 */
class SettingsControllerTest extends ControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.setting');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Settings = new TestSettingsController();
		$this->Settings->constructClasses();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Settings);

		parent::tearDown();
	}

/**
 * testAdminIndex method
 *
 * @return void
 */
	public function testAdminIndex() {
		$result = $this->testAction('/admin/settings/index');
		debug($result);
	}
/**
 * testAdminView method
 *
 * @return void
 */
	public function testAdminView() {
	}
/**
 * testAdminAdd method
 *
 * @return void
 */
	public function testAdminAdd() {
	}
/**
 * testAdminEdit method
 *
 * @return void
 */
	public function testAdminEdit() {
	}
/**
 * testAdminDelete method
 *
 * @return void
 */
	public function testAdminDelete() {
	}

}
