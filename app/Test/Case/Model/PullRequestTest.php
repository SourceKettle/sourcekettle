<?php
App::uses('PullRequest', 'Model');

/**
 * PullRequest Test Case
 *
 */
class PullRequestTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.pull_request',
		'app.requestor',
		'app.from_project',
		'app.to_project'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->PullRequest = ClassRegistry::init('PullRequest');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->PullRequest);

		parent::tearDown();
	}

}
