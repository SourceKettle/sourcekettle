<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('Model', 'Model');

class AppModel extends Model {

	// Is this an API request?
	public $_is_api = false;

	public $actsAs = array("Containable");

/**
 * Fix for deletable plugin
 */
	public function __deleteDependent($id, $cascade) {
		$this->_deleteDependent($id, $cascade);
	}

/**
 * Fix for deletable plugin
 */
	public function __deleteLinks($id) {
		$this->_deleteLinks($id);
	}

	public function exists($id = null) {
		if ($this->Behaviors->attached('SoftDelete')) {
			return $this->existsAndNotDeleted($id);
		} else {
			return parent::exists($id);
		}
	}
}
