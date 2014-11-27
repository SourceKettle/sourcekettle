<?php
App::uses('AppModel', 'Model');

class UserSetting extends AppModel {

	public $displayField = 'name';

	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	private $validNames = array(
		'UserInterface.theme',
		'UserInterface.terminology',
	);

	public function isValidName($name) {
		return in_array($name, $this->validNames);
	}

}
