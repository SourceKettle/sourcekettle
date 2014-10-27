<?php
/**
 * TaskPriorityFixture
 *
 */
class TaskPriorityFixture extends CakeTestFixture {

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
	public $import = array('model' => 'TaskPriority', 'records' => true);
	/*public $records = array(
		array(
			'id' => 1,
			'name' => 'minor',
			'created' => '2014-07-21 07:09:13',
			'modified' => '2014-07-21 07:09:13',
		),
		array(
			'id' => 2,
			'name' => 'major',
			'created' => '2014-07-21 07:09:13',
			'modified' => '2014-07-21 07:09:13',
		),
		array(
			'id' => 3,
			'name' => 'urgent',
			'created' => '2014-07-21 07:09:13',
			'modified' => '2014-07-21 07:09:13',
		),
		array(
			'id' => 4,
			'name' => 'blocker',
			'created' => '2014-07-21 07:09:13',
			'modified' => '2014-07-21 07:09:13',
		),
	);*/
}
