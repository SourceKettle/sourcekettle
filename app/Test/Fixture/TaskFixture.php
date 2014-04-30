<?php
/**
 * TaskFixture
 *
 */
class TaskFixture extends CakeTestFixture {

/**
 * Import
 *
 * @var array
 */
	public $import = array('model' => 'Task');

	public $records = array(
		array(
			'id' => 1,
			'project_id' => 2,
			'owner_id' => 3,
			'task_type_id' => 1,
			'task_priority_id' => 2,
			'task_status_id' => 3,
			'description' => 'Task number 1',
		),
		array(
			'id' => 2,
			'project_id' => 1,
			'owner_id' => 2,
			'task_type_id' => 2,
			'task_priority_id' => 1,
			'task_status_id' => 2,
			'description' => 'Task number 2',
		),
	);

}
