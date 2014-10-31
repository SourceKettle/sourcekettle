<?php
/**
 * TeamProjectGroupFixture
 *
 */
class TeamProjectGroupFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'team_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'project_group_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'access_level' => array('type' => 'integer', 'null' => false, 'default' => '1'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'team_id' => array('column' => 'team_id', 'unique' => 0),
			'project_group_id' => array('column' => 'project_group_id', 'unique' => 0)
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
			'team_id' => 1,
			'project_group_id' => 1,
			'access_level' => 1
		),
	);

}
