<?php
class LinkStoriesToProjectsAndAddEpics extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'linkStoriesToProjectsAndAddEpics';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'epics' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
					'project_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
					'subject' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_bin', 'charset' => 'utf8'),
					'description' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_bin', 'charset' => 'utf8'),
					'creator_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
					'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
					'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'project_id' => array('column' => 'project_id', 'unique' => 0),
						'creator_id' => array('column' => 'creator_id', 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_bin', 'engine' => 'InnoDB'),
				),
			),
			'create_field' => array(
				'stories' => array(
					'project_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index', 'after' => 'id'),
					'epic_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'key' => 'index', 'after' => 'project_id'),
					'indexes' => array(
						'project_id' => array('column' => 'project_id', 'unique' => 0),
						'fk_epic_id' => array('column' => 'epic_id', 'unique' => 0),
					),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'epics'
			),
			'drop_field' => array(
				'stories' => array('project_id', 'epic_id', 'indexes' => array('project_id', 'fk_epic_id')),
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
