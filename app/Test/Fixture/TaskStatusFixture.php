<?php
/**
 * TaskStatusFixture
 *
 */
class TaskStatusFixture extends CakeTestFixture {

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
	public $import = array('model' => 'TaskStatus', 'records' => true);
	/*public $records = array(
		array(
			'id' => 1,
			'name' => 'open',
			'created' => '2014-07-21 07:09:13',
			'modified' => '2014-07-21 07:09:13',
		),
		array(
			'id' => 2,
			'name' => 'in progress',
			'created' => '2014-07-21 07:09:13',
			'modified' => '2014-07-21 07:09:13',
		),
		array(
			'id' => 3,
			'name' => 'resolved',
			'created' => '2014-07-21 07:09:13',
			'modified' => '2014-07-21 07:09:13',
		),
		array(
			'id' => 4,
			'name' => 'closed',
			'created' => '2014-07-21 07:09:13',
			'modified' => '2014-07-21 07:09:13',
		),
		array(
			'id' => 5,
			'name' => 'dropped',
			'created' => '2014-07-21 07:09:13',
			'modified' => '2014-07-21 07:09:13',
		),
	);*/

}
