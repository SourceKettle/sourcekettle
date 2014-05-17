<?php
App::uses('Attachment', 'Model');
App::uses('File', 'Utility');

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
		foreach ($this->Attachment->mimeText as $mime) {
			$this->assertTrue($this->Attachment->renderable($mime));
		}
		foreach ($this->Attachment->mimeImage as $mime) {
			$this->assertTrue($this->Attachment->renderable($mime));
		}
		foreach ($this->Attachment->mimeVideo as $mime) {
			$this->assertTrue($this->Attachment->renderable($mime));
		}
		foreach ($this->Attachment->mimeOthers as $mime) {
			$this->assertFalse($this->Attachment->renderable($mime));
		}

		$this->assertNull($this->Attachment->renderable());
		$this->Attachment->id = 1;
		$this->assertFalse($this->Attachment->renderable());
		$this->Attachment->id = 2;
		$this->assertTrue($this->Attachment->renderable());
	}

/**
 * testUpload method
 *
 * @return void
 */
	public function testUpload() {

		// Prepare a temporary file
		$content = "This is a test of the emergency broadcast system.\nDo not adjust your shoes.";
		$tempfile = tempnam(ini_get('upload_tmp_dir'), 'sourcekettle-test');
		$fh = fopen($tempfile, 'w');
		fprintf($fh, $content);
		fclose($fh);

		$attachment = array('Attachment' => array(
			"FileUploadTest" => array(
				"name" => "FileUploadTest",
				"tmp_name" => $tempfile,
			),
		));

		// Upload without project - should return null
		$saved = $this->Attachment->upload($attachment, 'Project', 2, array('callbacks' => false));
		$this->assertNull($saved);


		// Upload with project - should get an object back
		$this->Attachment->Project->id = 2;
		$saved = $this->Attachment->upload($attachment, 'Project', 2, array('callbacks' => false));
		
		// Creation date will change with every test run, make sure it exists then remove for comparison
		$this->assertNotNull($saved['Attachment']['created']);
		unset($saved['Attachment']['created']);
		
		$this->assertEquals($saved, array(
			'Attachment' => array(
			'name' => 'FileUploadTest',
			'mime' => 'text/plain',
			'content' => $content,
			'size' => strlen($content),
			'md5' => md5($content),
			'project_id' => 2,
			'model' => 'Project',
			'model_id' => 2,
			'id' => '11'
			)
		));

		// Upload without any valid files - should return null
		$fake = array('Attachment' => array(
			'fake' => array(
				'name' => 'fake',
				'tmp_name' => 'nonexistent_file_gribble',
			),
		));
		$saved = $this->Attachment->upload($fake, 'Project', 2, array('callbacks' => false));
		$this->assertNull($saved);
	}

/**
 * testGetTitleForHistory method
 *
 * @return void
 */
	public function testGetTitleForHistory() {
		$this->assertEqual($this->Attachment->getTitleForHistory(1), "Lorem ipsum dolor sit amet");
		$this->assertNull($this->Attachment->getTitleForHistory(0));
	}

}
