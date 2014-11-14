<?php
/**
 * TeamsUserFixture
 *
 */
class TeamsUserFixture extends CakeTestFixture {

/**
 * Import
 *
 * @var array
 */
	public $import = array('table' => 'teams_users');

/**
 * Records
 *
 * @var array
 */
	public $records = array(

		// PHP devs
		array(
			'id' => 1,
			'team_id' => 1,
			'user_id' => 13
		),
		array(
			'id' => 2,
			'team_id' => 1,
			'user_id' => 16
		),
		array(
			'id' => 3,
			'team_id' => 1,
			'user_id' => 17
		),
		array(
			'id' => 4,
			'team_id' => 1,
			'user_id' => 19
		),

		// Java devs
		array(
			'id' => 5,
			'team_id' => 2,
			'user_id' => 14
		),
		array(
			'id' => 6,
			'team_id' => 2,
			'user_id' => 17
		),
		array(
			'id' => 7,
			'team_id' => 2,
			'user_id' => 18
		),
		array(
			'id' => 8,
			'team_id' => 2,
			'user_id' => 19
		),

		// Python devs
		array(
			'id' => 9,
			'team_id' => 3,
			'user_id' => 15
		),
		array(
			'id' => 10,
			'team_id' => 3,
			'user_id' => 16
		),
		array(
			'id' => 11,
			'team_id' => 3,
			'user_id' => 18
		),
		array(
			'id' => 12,
			'team_id' => 3,
			'user_id' => 19
		),

		// Perl devs
		array(
			'id' => 13,
			'team_id' => 4,
			'user_id' => 20
		),
		array(
			'id' => 14,
			'team_id' => 4,
			'user_id' => 21
		),

		// Misc
		array(
			'id' => 15,
			'team_id' => 5,
			'user_id' => 2,
		),
	);

}
