<?php
/**
 * PullRequestFixture
 *
 */
class PullRequestFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'requestor_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'from_project_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'to_project_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'approved' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'approved_by' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'approved_time' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'requestor_id' => array('column' => 'requestor_id', 'unique' => 0),
			'from_project_id' => array('column' => 'from_project_id', 'unique' => 0),
			'to_project_id' => array('column' => 'to_project_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'requestor_id' => 1,
			'from_project_id' => 1,
			'to_project_id' => 1,
			'approved' => 1,
			'approved_by' => 1,
			'approved_time' => '2014-12-17 08:54:57'
		),
	);

}
