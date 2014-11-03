<?php
/**
 * TeamFixture
 *
 */
class TeamFixture extends CakeTestFixture {

	 public $import = array('model' => 'Team');

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'name' => 'PHP developers',
			'description' => 'Devs who code in PHP'
		),
		array(
			'id' => 2,
			'name' => 'Java developers',
			'description' => 'Devs who code in Java'
		),
		array(
			'id' => 3,
			'name' => 'Python developers',
			'description' => 'Devs who code in Python'
		),
		array(
			'id' => 4,
			'name' => 'Perl developers',
			'description' => 'Devs who code in Perl'
		),
	);

}
