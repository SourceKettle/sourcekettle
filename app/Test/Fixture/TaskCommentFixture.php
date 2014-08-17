<?php
/**
 * TaskCommentFixture
 *
 */
class TaskCommentFixture extends CakeTestFixture {

/**
 * Import
 *
 * @var array
 */
	public $import = array('model' => 'TaskComment', 'records' => false);
	
	public $records = array(
		array(
			'id' => 1,
			'task_id' => 2,
			'user_id' => 2,
			'comment' => 'I like toast',
		),
		array(
			'id' => 2,
			'task_id' => 2,
			'user_id' => 1,
			'comment' => 'I do not like toast',
		),
		array(
			'id' => 3,
			'task_id' => 2,
			'user_id' => 3,
			'comment' => 'I have no strong feelings one way or the other about toast',
		),
	);
}
