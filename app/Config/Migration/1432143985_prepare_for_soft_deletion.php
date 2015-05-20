<?php
class PrepareForSoftDeletion extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'prepare_for_soft_deletion';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'attachments' => array(
					'modified' => array('type' => 'datetime', 'null' => false, 'default' => null, 'after' => 'created'),
					'deleted' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1, 'unsigned' => false, 'after' => 'modified'),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => null, 'after' => 'deleted'),
				),
				'epics' => array(
					'deleted' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1, 'unsigned' => false, 'after' => 'modified'),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => null, 'after' => 'deleted'),
				),
				'project_groups' => array(
					'created' => array('type' => 'datetime', 'null' => false, 'default' => null, 'after' => 'description'),
					'modified' => array('type' => 'datetime', 'null' => false, 'default' => null, 'after' => 'created'),
					'deleted' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1, 'unsigned' => false, 'after' => 'modified'),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => null, 'after' => 'deleted'),
				),
				'stories' => array(
					'deleted' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1, 'unsigned' => false, 'after' => 'modified'),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => null, 'after' => 'deleted'),
				),
				'teams' => array(
					'created' => array('type' => 'datetime', 'null' => false, 'default' => null, 'after' => 'description'),
					'modified' => array('type' => 'datetime', 'null' => false, 'default' => null, 'after' => 'created'),
					'deleted' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1, 'unsigned' => false, 'after' => 'modified'),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => null, 'after' => 'deleted'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'attachments' => array('modified', 'deleted', 'deleted_date'),
				'epics' => array('deleted', 'deleted_date'),
				'project_groups' => array('created', 'modified', 'deleted', 'deleted_date'),
				'stories' => array('deleted', 'deleted_date'),
				'teams' => array('created', 'modified', 'deleted', 'deleted_date'),
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
