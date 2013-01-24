<?php
/**
*
* SSH key model for the DevTrack system
* Stores the SSH keys for a user in the system
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
/**
 * SshKey Model
 *
 * @property User $User
 */
class SshKey extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'comment';
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'A invalid user id was given',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'key' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter your SSH key',
				'allowEmpty' => false,
				'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'comment' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter a comment for the SSH key to identify it',
				'allowEmpty' => false,
				'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
