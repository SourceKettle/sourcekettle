<?php
App::uses('Attachment', 'Model');

/**
 * Attachment Test Case
 *
 */
class AttachmentTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.attachment',
		'app.project',
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Attachment = ClassRegistry::init('Attachment');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Attachment);

		parent::tearDown();
	}

/**
 * testRenderable method
 *
 * @return void
 */
	public function testRenderable() {
	}

/**
 * testUpload method
 *
 * @return void
 */
	public function testUpload() {
	}

/**
 * testGetTitleForHistory method
 *
 * @return void
 */
	public function testGetTitleForHistory() {
	}

}
