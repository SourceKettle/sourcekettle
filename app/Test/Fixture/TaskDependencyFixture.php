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
	/*public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'child_task_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'index'),
		'parent_task_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'index'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'child_task_id' => array('column' => 'child_task_id', 'unique' => 0),
			'parent_task_id' => array('column' => 'parent_task_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_bin', 'engine' => 'InnoDB')
	);*/

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'child_task_id' => 1,
			'parent_task_id' => 2,
			'created' => '2014-04-29 10:38:45',
			'modified' => '2014-04-29 10:38:45'
		),
	);

}
