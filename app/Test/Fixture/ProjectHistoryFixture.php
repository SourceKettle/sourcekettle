<?php
/**
 * ProjectHistoryFixture
 *
 */
class ProjectHistoryFixture extends CakeTestFixture {

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
			'model' => 'Lorem ipsum dolor sit a',
			'row_id' => 1,
			'row_field' => 'Lorem ipsum dolor sit amet',
			'user_id' => 1,
			'user_name' => 'Lorem ipsum dolor sit amet',
			'row_title' => 'Lorem ipsum dolor sit amet',
			'row_field_old' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'row_field_new' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'created' => '2014-07-23 15:01:12',
			'modified' => '2014-07-23 15:01:12'
		),
		array(
			'id' => 2,
			'project_id' => 2,
			'model' => 'Lorem ipsum dolor sit a',
			'row_id' => 2,
			'row_field' => 'Lorem ipsum dolor sit amet',
			'user_id' => 2,
			'user_name' => 'Lorem ipsum dolor sit amet',
			'row_title' => 'Lorem ipsum dolor sit amet',
			'row_field_old' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'row_field_new' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'created' => '2014-07-23 15:01:12',
			'modified' => '2014-07-23 15:01:12'
		),
		array(
			'id' => 3,
			'project_id' => 3,
			'model' => 'Lorem ipsum dolor sit a',
			'row_id' => 3,
			'row_field' => 'Lorem ipsum dolor sit amet',
			'user_id' => 3,
			'user_name' => 'Lorem ipsum dolor sit amet',
			'row_title' => 'Lorem ipsum dolor sit amet',
			'row_field_old' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'row_field_new' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'created' => '2014-07-23 15:01:12',
			'modified' => '2014-07-23 15:01:12'
		),
		array(
			'id' => 4,
			'project_id' => 4,
			'model' => 'Lorem ipsum dolor sit a',
			'row_id' => 4,
			'row_field' => 'Lorem ipsum dolor sit amet',
			'user_id' => 4,
			'user_name' => 'Lorem ipsum dolor sit amet',
			'row_title' => 'Lorem ipsum dolor sit amet',
			'row_field_old' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'row_field_new' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'created' => '2014-07-23 15:01:12',
			'modified' => '2014-07-23 15:01:12'
		),
		array(
			'id' => 5,
			'project_id' => 5,
			'model' => 'Lorem ipsum dolor sit a',
			'row_id' => 5,
			'row_field' => 'Lorem ipsum dolor sit amet',
			'user_id' => 5,
			'user_name' => 'Lorem ipsum dolor sit amet',
			'row_title' => 'Lorem ipsum dolor sit amet',
			'row_field_old' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'row_field_new' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
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
