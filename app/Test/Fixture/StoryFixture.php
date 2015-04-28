<?php
/**
 * StoryFixture
 *
 */
class StoryFixture extends CakeTestFixture {

/**
 * Import
 *
 * @var array
 */
	public $import = array('model' => 'Story');

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '1',
			'project_id' => '2',
			'subject' => 'See the Fnords',
			'description' => 'As a member of the Illuminati, I want to see the Fnords, so that I may rule the world',
			'creator_id' => '6',
			'story_points' => null,
			'created' => '2015-02-14 17:39:13',
			'modified' => '2015-02-14 18:33:47'
		),
		array(
			'id' => '2',
			'project_id' => '2',
			'subject' => 'Shoot the Moon',
			'description' => 'As a trainee destroyer of worlds I would like to shoot the moon so that I can get my aim in before I try blowing up a planet',
			'creator_id' => '6',
			'story_points' => null,
			'created' => '2015-02-14 17:42:12',
			'modified' => '2015-02-14 17:42:12'
		),
		array(
			'id' => '3',
			'project_id' => '2',
			'subject' => 'Tell a Story',
			'description' => 'As a user, I would like to tell the developers a story so that they might actually give me a system I want to use',
			'creator_id' => '6',
			'story_points' => null,
			'created' => '2015-02-14 17:42:35',
			'modified' => '2015-02-14 17:42:35'
		),
		array(
			'id' => '4',
			'project_id' => '3',
			'subject' => 'First story',
			'description' => 'In the beginning, there was this story, with ID 4 and public ID 1',
			'creator_id' => '6',
			'story_points' => null,
			'created' => '2015-02-14 17:43:32',
			'modified' => '2015-02-14 18:32:58'
		),
		array(
			'id' => '5',
			'project_id' => '3',
			'subject' => 'Last story',
			'description' => 'In the end, there was this story, with ID 5 and public ID 2',
			'creator_id' => '6',
			'story_points' => null,
			'created' => '2015-02-14 18:01:04',
			'modified' => '2015-02-14 18:01:04'
		),
	);

}
