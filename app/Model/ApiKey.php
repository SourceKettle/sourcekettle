<?php
/**
 *
 * API key model for the DevTrack system
 * Stores the API keys for a user in the system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/SourceKettle/devtrack
 * @package       DevTrack.Model
 * @since         DevTrack v 0.1
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
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter your API key',
				'allowEmpty' => false,
				'required' => false,
			),
		),
		'comment' => array(
			'notempty' => array(
				'rule' => array('notempty'),
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
