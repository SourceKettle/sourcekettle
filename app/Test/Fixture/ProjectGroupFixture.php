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
			'name' => 'php_projects',
			'description' => 'Some projects written in PHP'
		),
		array(
			'id' => 2,
			'name' => 'java_projects',
			'description' => 'Some projects written in Java'
		),
		array(
			'id' => 3,
			'name' => 'python_projects',
			'description' => 'Some projects written in Python'
		),
	);

}
