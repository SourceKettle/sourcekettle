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


class SshKey extends AppModel {

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
				'allowEmpty' => false,
				'required' => true,
			),
		),
		'key' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter your SSH key',
				'allowEmpty' => false,
				'required' => true,
			),
		),
		'comment' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter a comment for the SSH key to identify it',
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

/**
 * Given an SSH key, possibly with embedded comment, possibly missing the key type,
 * spit out an array containing th ecomment (if any) and the key with correct type.
 * If the key is invalid, return false.
 */
	private function __sanitiseKey($key) {

		// Check it is correctly formatted: optional type, mandatory base64-encoded key, optional comment
		if (!preg_match('/^\s*((ssh-(rsa|dss))\s+)?([a-zA-Z0-9+\/\r\n]+={0,2})(\s+(.+))?\s*$/', $key, $matches)) {
			return false;
		}
		
		// Split out the key components
		$type = $matches[2];
		$key  = $matches[4];
		$comment = false;
		if (isset($matches[6])) {
			$comment = $matches[6];
		}


		// If they just pasted in the key without the type prefix, work it out from the key
		// NB these strings are [0007]ssh-rsa and [0007]ssh-dss base64-encoded
		if (!isset($type) || empty($type) || !in_array($type, array('ssh-rsa', 'ssh-dss'))) {
			if (substr($key, 0, 15) == 'AAAAB3NzaC1kc3M') {
				$type = 'ssh-dss';
			} elseif (substr($key, 0, 15) == 'AAAAB3NzaC1yc2E') {
				$type = 'ssh-rsa';
			} else {
				return false;
			}
		}

		return array('key' => "$type $key", 'comment' => $comment);
		
	}

	public function beforeValidate($options = array()) {

		// Key is required...
		if (!isset($this->data[$this->alias]['key'])) {
			return false;
		}
		
		$sanitised = $this->__sanitiseKey($this->data[$this->alias]['key']);

		if (!$sanitised) {
			return false;
		}

		$this->data[$this->alias]['key'] = $sanitised['key'];

		// If they didn't provide a comment but the key *does* contain a comment, just use that
		if ($sanitised['comment'] && !isset($this->data[$this->alias]['comment'])) {
			$this->data[$this->alias]['comment'] = $sanitised['comment'];
		}
		return true;
	}

	public function afterFind($results, $primary) {

		$correct = array();
		while ($result = array_shift($results)) {
			$sanitised = $this->__sanitiseKey($result[$this->alias]['key']);
			if (!$sanitised) {
				continue;
			}
	
			$result[$this->alias]['key'] = $sanitised['key'];
			if ($sanitised['comment'] && !@$result[$this->alias]['comment']) {
				$result[$this->alias]['comment'] = $sanitised['comment'];
			}
			$correct[] = $result;
		}

		return $correct;
	}

}
