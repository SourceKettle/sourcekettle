<?php
App::uses('PagesController', 'Controller');
require_once __DIR__ . DS . 'AppControllerTest.php';
/**
 * PagesController Test Case
 *
 */
class PagesControllerTest extends AppControllerTest {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.setting',
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
		'app.email_confirmation_key',
		'app.ssh_key',
		'app.api_key',
		'app.lost_password_key',
	);

	public function setUp() {
		parent::setUp("Pages");
	}
/**
 * testDisplay method
 *
 * @return void
 */
	public function testDisplayEmptyNotLoggedIn() {
		$this->testAction('/pages', array('return' => 'view', 'method' => 'get'));
		// We should be redirected to the home page
		$this->assertNotNull($this->headers);
		$this->assertNotNull(@$this->headers['Location']);
		$this->assertEquals(Router::url('/', true), $this->headers['Location']);
	}

	public function testDisplayEmptyLoggedIn() {
		$this->_fakeLogin(2);
		$this->testAction('/pages', array('return' => 'view', 'method' => 'get'));

		// We should be redirected to the home page
		$this->assertNotNull($this->headers);
		$this->assertNotNull(@$this->headers['Location']);
		$this->assertEquals(Router::url('/', true), $this->headers['Location']);
	}

	public function testDisplayPages() {
		$baseDir = realpath(dirname(dirname(dirname(__DIR__))).'/View/Pages');
		$baseFolder = new Folder($baseDir);
		$files = $baseFolder->read(true, false, true);
		foreach ($files[1] as $file) {
			if (!preg_match('/([^\/]+)\.ctp$/', $file, $matches)) {
				continue;
			}
			$rendered = $this->testAction('/pages/'.$matches[1], array('return' => 'contents', 'method' => 'get'));

			$real = $this->controller->render($matches[1]);
			$this->assertEquals($real->__toString(), $rendered);
		}
	}


/**
 * testHome method
 *
 * @return void
 */
	public function testHomeNotLoggedIn() {

		// Logged out - show the home page at /
		$rendered = $this->testAction('/', array('return' => 'view', 'method' => 'get'));
		$expected = $this->testAction('/pages/home', array('return' => 'view', 'method' => 'get'));
		$this->assertEquals($expected, $rendered);
	}

	public function testHomeLoggedIn() {
		// Logged in - redirect to the dashboard
		$this->_fakeLogin(2);
		$this->testAction('/', array('return' => 'result', 'method' => 'get'));

		// We should be redirected to the dashboard
		$this->assertNotNull($this->headers);
		$this->assertNotNull(@$this->headers['Location']);
		$this->assertEquals(Router::url('/dashboard', true), $this->headers['Location']);
	}

}
