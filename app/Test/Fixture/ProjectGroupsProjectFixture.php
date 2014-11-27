<?php
/**
 * ProjectGroupsProjectFixture
 *
 */
class ProjectGroupsProjectFixture extends CakeTestFixture {

/**
 * Import
 *
 * @var array
 */
 	public $import = array('table' => 'project_groups_projects');

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'project_group_id' => 1,
			'project_id' => 6
		),
		array(
			'id' => 2,
			'project_group_id' => 1,
			'project_id' => 7
		),
		array(
			'id' => 3,
			'project_group_id' => 2,
			'project_id' => 8
		),
		array(
			'id' => 4,
			'project_group_id' => 2,
			'project_id' => 9
		),
		array(
			'id' => 5,
			'project_group_id' => 3,
			'project_id' => 10
		),
		array(
			'id' => 6,
			'project_group_id' => 3,
			'project_id' => 11
		),
	);

}
