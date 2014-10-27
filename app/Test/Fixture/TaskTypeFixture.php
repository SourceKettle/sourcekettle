<?php
/**
 * TaskTypeFixture
 *
 */
class TaskTypeFixture extends CakeTestFixture {

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
	public $import = array('model' => 'TaskType', 'records' => true);
	/*public $records = array(
		array(
			'id' => 1,
			'name' => 'bug',
			'created' => '2014-07-21 07:09:13',
			'modified' => '2014-07-21 07:09:13',
		),
		array(
			'id' => 2,
			'name' => 'duplicate',
			'created' => '2014-07-21 07:09:13',
			'modified' => '2014-07-21 07:09:13',
		),
		array(
			'id' => 3,
			'name' => 'enhancement',
			'created' => '2014-07-21 07:09:13',
			'modified' => '2014-07-21 07:09:13',
		),
		array(
			'id' => 4,
			'name' => 'invalid',
			'created' => '2014-07-21 07:09:13',
			'modified' => '2014-07-21 07:09:13',
		),
		array(
			'id' => 5,
			'name' => 'question',
			'created' => '2014-07-21 07:09:13',
			'modified' => '2014-07-21 07:09:13',
		),
		array(
			'id' => 6,
			'name' => 'wontfix',
			'created' => '2014-07-21 07:09:13',
			'modified' => '2014-07-21 07:09:13',
		),
		array(
			'id' => 7,
			'name' => 'documentation',
			'created' => '2014-07-21 07:09:13',
			'modified' => '2014-07-21 07:09:13',
		),
		array(
			'id' => 8,
			'name' => 'meeting',
			'created' => '2014-07-21 07:09:13',
			'modified' => '2014-07-21 07:09:13',
		),
	);*/
}
