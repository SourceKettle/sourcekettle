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

/**
 * testDisplay method
 *
 * @return void
 */
	public function testDisplayAbout() {
		$this->testAction('/about', array('return' => 'contents', 'method' => 'get'));
		$this->assertContains('SourceKettle is built using a variety of open-source software', $this->view);

		$this->_fakeLogin(2);
		$this->testAction('/pages/about', array('return' => 'contents', 'method' => 'get'));
		$this->assertContains('SourceKettle is built using a variety of open-source software', $this->view);
	}

	public function testDisplayHome() {
		$this->testAction('/pages/home', array('return' => 'view', 'method' => 'get'));
		$this->assertContains('<a href="/login">Click here</a> to get started.', $this->view);

		$this->_fakeLogin(2);
		$this->testAction('/pages/home', array('return' => 'view', 'method' => 'get'));
		$this->assertContains('<a href="/login">Click here</a> to get started.', $this->view);
	}

	public function testDisplayEmpty() {
		$this->testAction('/pages', array('return' => 'view', 'method' => 'get'));
		$this->assertRegexp('/\/$/', $this->headers['Location']);

		$this->_fakeLogin(2);
		$this->testAction('/pages', array('return' => 'view', 'method' => 'get'));
		$this->assertRegexp('/\/$/', $this->headers['Location']);
	}

/**
 * testHome method
 *
 * @return void
 */
	public function testHome() {

		// Logged out - show the home page at /
		$this->testAction('/', array('return' => 'view', 'method' => 'get'));
		$this->assertContains('<a href="/login">Click here</a> to get started.', $this->view);

		// Logged in - redirect to the dashboard
		$this->_fakeLogin(2);
		$this->testAction('/', array('return' => 'result', 'method' => 'get'));
		$this->assertRegexp('/\/dashboard$/', $this->headers['Location']);
	}

}
