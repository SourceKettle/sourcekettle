<?php
/**
 * TaskDependencyFixture
 *
 */
class TaskDependencyFixture extends CakeTestFixture {

	// Force InnoDB table type so we can test transactions
	public function create($db) {
	    $this->fields['tableParameters']['engine'] = 'InnoDB';
	    return parent::create($db);
	}

/**
 * Fields
 *
 * @var array
 */
 	public $import = array('table' => 'task_dependencies');

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'child_task_id' => 2,
			'parent_task_id' => 1,
			'created' => '2014-04-29 10:38:45',
			'modified' => '2014-04-29 10:38:45'
		),
		array(
			'id' => 2,
			'child_task_id' => 2,
			'parent_task_id' => 4,
			'created' => '2014-04-29 10:38:45',
			'modified' => '2014-04-29 10:38:45'
		),
		array(
			'id' => 3,
			'child_task_id' => 5,
			'parent_task_id' => 7,
			'created' => '2014-04-29 10:38:45',
			'modified' => '2014-04-29 10:38:45'
		),

		// Dependency loop here...
		array(
			'id' => 4,
			'child_task_id' => 21,
			'parent_task_id' => 22,
			'created' => '2014-04-29 10:38:45',
			'modified' => '2014-04-29 10:38:45'
		),
		array(
			'id' => 5,
			'child_task_id' => 22,
			'parent_task_id' => 23,
			'created' => '2014-04-29 10:38:45',
			'modified' => '2014-04-29 10:38:45'
		),
		array(
			'id' => 6,
			'child_task_id' => 23,
			'parent_task_id' => 24,
			'created' => '2014-04-29 10:38:45',
			'modified' => '2014-04-29 10:38:45'
		),
		array(
			'id' => 7,
			'child_task_id' => 23,
			'parent_task_id' => 21,
			'created' => '2014-04-29 10:38:45',
			'modified' => '2014-04-29 10:38:45'
		),
	);

}
