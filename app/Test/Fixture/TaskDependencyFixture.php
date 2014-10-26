<?php
/**
 * TaskDependencyFixture
 *
 */
class TaskDependencyFixture extends CakeTestFixture {

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
	);

}
