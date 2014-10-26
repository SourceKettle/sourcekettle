<?php
/**
 * ProjectBurndownLogFixture
 *
 */
class ProjectBurndownLogFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'timestamp' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'project_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'task_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'minutes_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'points_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'project_id' => array('column' => 'project_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'timestamp' => '2014-10-26 07:36:25',
			'project_id' => 1,
			'task_count' => 1,
			'minutes_count' => 1,
			'points_count' => 1
		),
	);

}
