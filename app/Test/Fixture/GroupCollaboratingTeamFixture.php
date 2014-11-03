<?php
/**
 * GroupCollaboratingTeamFixture
 *
 */
class GroupCollaboratingTeamFixture extends CakeTestFixture {

	public $import = array('model' => 'GroupCollaboratingTeam');

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		// PHP devs are admins on PHP project group, java->java, python->python
		array(
			'id' => 1,
			'project_group_id' => 1,
			'team_id' => 1,
			'access_level' => 2
		),
		array(
			'id' => 2,
			'project_group_id' => 2,
			'team_id' => 2,
			'access_level' => 2
		),
		array(
			'id' => 3,
			'project_group_id' => 3,
			'team_id' => 3,
			'access_level' => 2
		),

		// Each dev group is also a user on one other project type
		array(
			'id' => 4,
			'project_group_id' => 1,
			'team_id' => 3,
			'access_level' => 1
		),
		array(
			'id' => 5,
			'project_group_id' => 2,
			'team_id' => 1,
			'access_level' => 1
		),
		array(
			'id' => 6,
			'project_group_id' => 3,
			'team_id' => 2,
			'access_level' => 1
		),
	);

}
