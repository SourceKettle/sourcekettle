<?php
/**
 * RepoTypeFixture
 *
 */
class RepoTypeFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 2, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
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
			'name' => 'None',
			'created' => '2012-06-01 12:46:57',
			'modified' => '2012-06-01 12:46:57'
		),
		array(
			'id' => 2,
			'name' => 'Git',
			'created' => '2012-06-01 12:46:57',
			'modified' => '2012-06-01 12:46:57'
		),
		array(
			'id' => 3,
			'name' => 'SVN',
			'created' => '2012-06-01 12:46:57',
			'modified' => '2012-06-01 12:46:57'
		),
	);
}
