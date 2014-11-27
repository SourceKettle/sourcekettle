<?php
/**
 * UserSettingFixture
 *
 */
class UserSettingFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $import = array('model' => 'UserSetting');
/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'user_id' => 1,
			'name' => 'UserInterface.theme',
			'value' => 'spruce',
			'created' => '2014-10-28 22:29:14',
			'modified' => '2014-10-28 22:29:14'
		),
		array(
			'id' => 2,
			'user_id' => 1,
			'name' => 'UserInterface.terminology',
			'value' => 'scrum',
			'created' => '2014-10-28 22:29:14',
			'modified' => '2014-10-28 22:29:14'
		),
		array(
			'id' => 3,
			'user_id' => 1,
			'name' => 'UserInterface.goose',
			'value' => 'spruce',
			'created' => '2014-10-28 22:29:14',
			'modified' => '2014-10-28 22:29:14'
		),
	);

}
