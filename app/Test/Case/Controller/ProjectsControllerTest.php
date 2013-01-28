<?php
App::uses('ProjectsController', 'Controller');

/**
 * TestProjectsController *
 */
class TestProjectsController extends ProjectsController {

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
 * ProjectsController Test Case
 *
 */
class ProjectsControllerTestCase extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.project', 'app.repo_type', 'app.collaborator', 'app.user', 'app.email_confirmation_key', 'app.ssh_key');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Projects = new TestProjectsController();
		$this->Projects->constructClasses();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Projects);

		parent::tearDown();
	}

/**
 * testIndex method
 *
 * @return void
 */
	public function testIndex() {
	}
/**
 * testView method
 *
 * @return void
 */
	public function testView() {
	}
/**
 * testAdd method
 *
 * @return void
 */
	public function testAdd() {
	}
/**
 * testEdit method
 *
 * @return void
 */
	public function testEdit() {
	}
/**
 * testDelete method
 *
 * @return void
 */
	public function testDelete() {
	}
/**
 * testAdminIndex method
 *
 * @return void
 */
	public function testAdminIndex() {
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
