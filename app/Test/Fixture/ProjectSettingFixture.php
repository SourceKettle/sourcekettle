<?php
/**
 * ProjectSettingFixture
 *
 */
class ProjectSettingFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $import = array('model' => 'ProjectSetting');

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'project_id' => 2,
			'name' => 'Features.attachment_enabled',
			'value' => false,
			'created' => '2014-10-28 22:29:22',
			'modified' => '2014-10-28 22:29:22'
		),
		array(
			'id' => 2,
			'project_id' => 2,
			'name' => 'Features.source_enabled',
			'value' => true,
			'created' => '2014-10-28 22:29:22',
			'modified' => '2014-10-28 22:29:22'
		),
		array(
			'id' => 3,
			'project_id' => 2,
			'name' => 'Features.moose_enabled',
			'value' => true,
			'created' => '2014-10-28 22:29:22',
			'modified' => '2014-10-28 22:29:22'
		),
	);

}
