<?php
/**
 * CollaboratingTeamFixture
 *
 */
class CollaboratingTeamFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $import = array('model' => 'CollaboratingTeam');

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		// Direct mapping of perl dev team to the only perl project
		array(
			'id' => 1,
			'team_id' => 4,
			'project_id' => 12,
			'access_level' => 2
		),
	);

}
