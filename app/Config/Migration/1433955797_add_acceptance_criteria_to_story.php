<?php
class AddAcceptanceCriteriaToStory extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_acceptance_criteria_to_story';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'stories' => array(
					'acceptance_criteria' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_bin', 'charset' => 'utf8', 'after' => 'description'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'stories' => array('acceptance_criteria'),
			),
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
		return true;
	}
}
