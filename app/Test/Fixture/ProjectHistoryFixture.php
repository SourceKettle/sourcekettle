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

	public $import = array('model' => 'ProjectHistory');

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
			'project_id' => 1,
			'model' => 'task',
			'row_id' => 11,
			'row_field' => 'task_status_id',
			'user_id' => 1,
			'user_name' => '',
			'row_title' => '',
			'row_field_old' => '1',
			'row_field_new' => '2',
			'created' => '2014-07-23 15:10:34',
			'modified' => '2014-07-23 15:10:34'
		),
		array(
			'id' => 7,
			'project_id' => 1,
			'model' => 'task',
			'row_id' => 11,
			'row_field' => '+',
			'user_id' => 1,
			'user_name' => '',
			'row_title' => '',
			'row_field_old' => '',
			'row_field_new' => '',
			'created' => '2014-07-23 15:01:12',
			'modified' => '2014-07-23 15:01:12'
		),
	);

}
