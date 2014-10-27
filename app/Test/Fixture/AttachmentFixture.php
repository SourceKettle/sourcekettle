<?php
/**
 * AttachmentFixture
 *
 */
class AttachmentFixture extends CakeTestFixture {

	// Force InnoDB table type so we can test transactions
	public function create($db) {
	    $this->fields['tableParameters']['engine'] = 'InnoDB';
	    return parent::create($db);
	}
/**
 * Import
 *
 * @var array
 */
	public $import = array('model' => 'Attachment');

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'project_id' => 1,
			'model' => 'Lorem ipsum dolor sit amet',
			'model_id' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'mime' => 'Lorem ipsum dolor sit amet',
			'size' => 'Lorem ipsum dolor sit amet',
			'md5' => 'Lorem ipsum dolor sit amet',
			'content' => 'Lorem ipsum dolor sit amet',
			'created' => '2014-05-08 12:04:29'
		),
		array(
			'id' => 2,
			'project_id' => 2,
			'model' => 'Lorem ipsum dolor sit amet',
			'model_id' => 2,
			'name' => 'Lorem ipsum dolor sit amet',
			'mime' => 'text/plain',
			'size' => 'Lorem ipsum dolor sit amet',
			'md5' => 'Lorem ipsum dolor sit amet',
			'content' => 'Lorem ipsum dolor sit amet',
			'created' => '2014-05-08 12:04:29'
		),
		array(
			'id' => 3,
			'project_id' => 3,
			'model' => 'Lorem ipsum dolor sit amet',
			'model_id' => 3,
			'name' => 'Lorem ipsum dolor sit amet',
			'mime' => 'Lorem ipsum dolor sit amet',
			'size' => 'Lorem ipsum dolor sit amet',
			'md5' => 'Lorem ipsum dolor sit amet',
			'content' => 'Lorem ipsum dolor sit amet',
			'created' => '2014-05-08 12:04:29'
		),
		array(
			'id' => 4,
			'project_id' => 4,
			'model' => 'Lorem ipsum dolor sit amet',
			'model_id' => 4,
			'name' => 'Lorem ipsum dolor sit amet',
			'mime' => 'Lorem ipsum dolor sit amet',
			'size' => 'Lorem ipsum dolor sit amet',
			'md5' => 'Lorem ipsum dolor sit amet',
			'content' => 'Lorem ipsum dolor sit amet',
			'created' => '2014-05-08 12:04:29'
		),
		array(
			'id' => 5,
			'project_id' => 5,
			'model' => 'Lorem ipsum dolor sit amet',
			'model_id' => 5,
			'name' => 'Lorem ipsum dolor sit amet',
			'mime' => 'Lorem ipsum dolor sit amet',
			'size' => 'Lorem ipsum dolor sit amet',
			'md5' => 'Lorem ipsum dolor sit amet',
			'content' => 'Lorem ipsum dolor sit amet',
			'created' => '2014-05-08 12:04:29'
		),
		array(
			'id' => 6,
			'project_id' => 6,
			'model' => 'Lorem ipsum dolor sit amet',
			'model_id' => 6,
			'name' => 'Lorem ipsum dolor sit amet',
			'mime' => 'Lorem ipsum dolor sit amet',
			'size' => 'Lorem ipsum dolor sit amet',
			'md5' => 'Lorem ipsum dolor sit amet',
			'content' => 'Lorem ipsum dolor sit amet',
			'created' => '2014-05-08 12:04:29'
		),
		array(
			'id' => 7,
			'project_id' => 7,
			'model' => 'Lorem ipsum dolor sit amet',
			'model_id' => 7,
			'name' => 'Lorem ipsum dolor sit amet',
			'mime' => 'Lorem ipsum dolor sit amet',
			'size' => 'Lorem ipsum dolor sit amet',
			'md5' => 'Lorem ipsum dolor sit amet',
			'content' => 'Lorem ipsum dolor sit amet',
			'created' => '2014-05-08 12:04:29'
		),
		array(
			'id' => 8,
			'project_id' => 8,
			'model' => 'Lorem ipsum dolor sit amet',
			'model_id' => 8,
			'name' => 'Lorem ipsum dolor sit amet',
			'mime' => 'Lorem ipsum dolor sit amet',
			'size' => 'Lorem ipsum dolor sit amet',
			'md5' => 'Lorem ipsum dolor sit amet',
			'content' => 'Lorem ipsum dolor sit amet',
			'created' => '2014-05-08 12:04:29'
		),
		array(
			'id' => 9,
			'project_id' => 9,
			'model' => 'Lorem ipsum dolor sit amet',
			'model_id' => 9,
			'name' => 'Lorem ipsum dolor sit amet',
			'mime' => 'Lorem ipsum dolor sit amet',
			'size' => 'Lorem ipsum dolor sit amet',
			'md5' => 'Lorem ipsum dolor sit amet',
			'content' => 'Lorem ipsum dolor sit amet',
			'created' => '2014-05-08 12:04:29'
		),
		array(
			'id' => 10,
			'project_id' => 10,
			'model' => 'Lorem ipsum dolor sit amet',
			'model_id' => 10,
			'name' => 'Lorem ipsum dolor sit amet',
			'mime' => 'Lorem ipsum dolor sit amet',
			'size' => 'Lorem ipsum dolor sit amet',
			'md5' => 'Lorem ipsum dolor sit amet',
			'content' => 'Lorem ipsum dolor sit amet',
			'created' => '2014-05-08 12:04:29'
		),
	);

}
