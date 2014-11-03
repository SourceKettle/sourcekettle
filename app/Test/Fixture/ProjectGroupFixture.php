<?php
/**
 * ProjectGroupFixture
 *
 */
class ProjectGroupFixture extends CakeTestFixture {


	public $import = array('model' => 'ProjectGroup');
/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'name' => 'PHP projects',
			'description' => 'Some projects written in PHP'
		),
		array(
			'id' => 2,
			'name' => 'Java projects',
			'description' => 'Some projects written in Java'
		),
		array(
			'id' => 3,
			'name' => 'Python projects',
			'description' => 'Some projects written in Python'
		),
	);

}
