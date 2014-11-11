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
			'name' => 'php_developers',
			'description' => 'Devs who code in PHP'
		),
		array(
			'id' => 2,
			'name' => 'java_developers',
			'description' => 'Devs who code in Java'
		),
		array(
			'id' => 3,
			'name' => 'python_developers',
			'description' => 'Devs who code in Python'
		),
		array(
			'id' => 4,
			'name' => 'perl_developers',
			'description' => 'Devs who code in Perl'
		),
	);

}
