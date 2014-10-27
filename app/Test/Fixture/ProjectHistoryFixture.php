<?php
/**
 * ProjectHistoryFixture
 *
 */
class ProjectHistoryFixture extends CakeTestFixture {

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
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'project_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'index'),
		'model' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 25, 'collate' => 'utf8_bin', 'charset' => 'utf8'),
		'row_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10),
		'row_field' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_bin', 'charset' => 'utf8'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10),
		'user_name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_bin', 'charset' => 'utf8'),
		'row_title' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_bin', 'charset' => 'utf8'),
		'row_field_old' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_bin', 'charset' => 'utf8'),
		'row_field_new' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_bin', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null, 'key' => 'index'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'modified' => array('column' => 'modified', 'unique' => 0),
			'created' => array('column' => 'modified', 'unique' => 0),
			'project_id' => array('column' => 'project_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_bin', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'project_id' => 1,
			'model' => 'collaborator',
			'row_id' => 2,
			'row_field' => 'access_level',
			'user_id' => 1,
			'user_name' => 'Mr Smith',
			'row_title' => 'Mr Admin',
			'row_field_old' => '1',
			'row_field_new' => '2',
			'created' => '2014-07-23 15:01:12',
			'modified' => '2014-07-23 15:01:12'
		),
		array(
			'id' => 2,
			'project_id' => 2,
			'model' => 'milestone',
			'row_id' => 3,
			'row_field' => 'is_open',
			'user_id' => 2,
			'user_name' => 'Mrs Smith',
			'row_title' => 'Longer <i>subject</i>',
			'row_field_old' => '1',
			'row_field_new' => '0',
			'created' => '2014-07-23 15:01:12',
			'modified' => '2014-07-23 15:01:12'
		),
		array(
			'id' => 3,
			'project_id' => 1,
			'model' => 'collaborator',
			'row_id' => 2,
			'row_field' => 'access_level',
			'user_id' => 1,
			'user_name' => 'Mr Smith',
			'row_title' => 'Mr Admin',
			'row_field_old' => '2',
			'row_field_new' => '1',
			'created' => '2014-07-23 15:02:12',
			'modified' => '2014-07-23 15:02:12'
		),
		array(
			'id' => 4,
			'project_id' => 3,
			'model' => 'time',
			'row_id' => 4,
			'row_field' => '+',
			'user_id' => 2,
			'user_name' => 'Mrs Smith',
			'row_title' => 'Logged time',
			'row_field_old' => null,
			'row_field_new' => null,
			'created' => '2014-07-23 15:01:12',
			'modified' => '2014-07-23 15:01:12'
		),
		array(
			'id' => 5,
			'project_id' => 3,
			'model' => 'time',
			'row_id' => 4,
			'row_field' => 'description',
			'user_id' => 2,
			'user_name' => 'Mrs Smith',
			'row_title' => 'Logged time',
			'row_field_old' => 'foo',
			'row_field_new' => 'bar',
			'created' => '2014-07-23 15:01:12',
			'modified' => '2014-07-23 15:01:12'
		),
		array(
			'id' => 6,
			'project_id' => 6,
			'model' => 'Lorem ipsum dolor sit a',
			'row_id' => 6,
			'row_field' => 'Lorem ipsum dolor sit amet',
			'user_id' => 6,
			'user_name' => 'Lorem ipsum dolor sit amet',
			'row_title' => 'Lorem ipsum dolor sit amet',
			'row_field_old' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'row_field_new' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'created' => '2014-07-23 15:01:12',
			'modified' => '2014-07-23 15:01:12'
		),
		array(
			'id' => 7,
			'project_id' => 7,
			'model' => 'Lorem ipsum dolor sit a',
			'row_id' => 7,
			'row_field' => 'Lorem ipsum dolor sit amet',
			'user_id' => 7,
			'user_name' => 'Lorem ipsum dolor sit amet',
			'row_title' => 'Lorem ipsum dolor sit amet',
			'row_field_old' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'row_field_new' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'created' => '2014-07-23 15:01:12',
			'modified' => '2014-07-23 15:01:12'
		),
		array(
			'id' => 8,
			'project_id' => 8,
			'model' => 'Lorem ipsum dolor sit a',
			'row_id' => 8,
			'row_field' => 'Lorem ipsum dolor sit amet',
			'user_id' => 8,
			'user_name' => 'Lorem ipsum dolor sit amet',
			'row_title' => 'Lorem ipsum dolor sit amet',
			'row_field_old' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'row_field_new' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'created' => '2014-07-23 15:01:12',
			'modified' => '2014-07-23 15:01:12'
		),
		array(
			'id' => 9,
			'project_id' => 9,
			'model' => 'Lorem ipsum dolor sit a',
			'row_id' => 9,
			'row_field' => 'Lorem ipsum dolor sit amet',
			'user_id' => 9,
			'user_name' => 'Lorem ipsum dolor sit amet',
			'row_title' => 'Lorem ipsum dolor sit amet',
			'row_field_old' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'row_field_new' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'created' => '2014-07-23 15:01:12',
			'modified' => '2014-07-23 15:01:12'
		),
		array(
			'id' => 10,
			'project_id' => 10,
			'model' => 'Lorem ipsum dolor sit a',
			'row_id' => 10,
			'row_field' => 'Lorem ipsum dolor sit amet',
			'user_id' => 10,
			'user_name' => 'Lorem ipsum dolor sit amet',
			'row_title' => 'Lorem ipsum dolor sit amet',
			'row_field_old' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'row_field_new' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'created' => '2014-07-23 15:01:12',
			'modified' => '2014-07-23 15:01:12'
		),
	);

}
