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
class ProjectsControllerTestCase extends ControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
    public $fixtures = array(
		'app.project',
		'app.project_history',
		'app.repo_type',
		'app.collaborator',
		'app.user',
		'app.task',
		'app.task_type',
		'app.task_dependency',
		'app.task_comment',
		'app.task_status',
		'app.task_priority',
		'app.time',
		'app.attachment',
		'app.source',
		'app.milestone',
		'app.setting',
	);

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
	public function testIndexGet() {
		/*$projects = $this->generate('Projects', array(
			'components' => array(
				'Session'
			)
		));*/
		/*$projects->Session->expects($this->once())
				->method('setFlash');*/
		//$this->Projects->Auth = $this->getMock('Auth', array('user'));
		//$this->Projects->Auth->Session = $this->getMock('SessionComponent', array('renew'), array(), '', false);
		$result = $this->testAction('/projects/view/1');
		debug($result);
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
