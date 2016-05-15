<?php
/**
 *
 * API key model for the SourceKettle system
 * Stores the API keys for a user in the system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.Model
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('AppModel', 'Model');

class ApiKey extends AppModel {

/**
 * Display field
 */
	public $displayField = 'comment';

/**
 * Validation rules
 */
	public $validate = array(
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'A invalid user id was given',
			),
		),
		'key' => array(
			'notblank' => array(
				'rule' => array('notblank'),
				'message' => 'Please enter your API key',
				'allowEmpty' => false,
				'required' => false,
			),
		),
		'comment' => array(
			'notblank' => array(
				'rule' => array('notblank'),
				'message' => 'Please enter a comment for the API key to identify it',
				'allowEmpty' => false,
				'required' => false,
			),
		),
	);

/**
 * belongsTo associations
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
		)
	);
}
