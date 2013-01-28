<?php
/**
 *
 * Email Confirmation Key model for the DevTrack system
 * Used for validating users by emails. Stores the key used to authenticate with.
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

class EmailConfirmationKey extends AppModel {

/**
 * Validation rules
 */
	public $validate = array(
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'A valid user id was not given',
			),
		),
		'key' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'A key was not given',
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
