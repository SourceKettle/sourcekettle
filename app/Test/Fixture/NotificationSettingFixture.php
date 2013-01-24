<?php
/**
 * NotificationSettingFixture
 *
 */
class NotificationSettingFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'unique'),
		'email_notifications' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'all_notifications' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'involed_notifications' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'user_id' => array('column' => 'user_id', 'unique' => 1)),
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
			'user_id' => 1,
			'email_notifications' => 1,
			'all_notifications' => 1,
			'involed_notifications' => 1,
			'created' => '2013-01-24 11:31:21',
			'modified' => '2013-01-24 11:31:21'
		),
	);
}
