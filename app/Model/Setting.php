<?php
/**
 *
 * Setting model for the DevTrack system
 * Represents core settings in the system
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

class Setting extends AppModel {

/**
 * Display field
 */
	public $displayField = 'name';

/**
 * Validation rules
 */
	public $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			),
		),
		// We may need to set e.g. LDAP fields to empty
		/*'value' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			),
		),*/
	);

/**
 * syncRequired function.
 * Notify the system that the keys need to be sync'd
 */
	public function syncRequired() {
		$setting = $this->findByName('sync_required', array('id'));
		$this->id = $setting['Setting']['id'];
		$this->set('value', '1');
		$this->save();
	}
}
