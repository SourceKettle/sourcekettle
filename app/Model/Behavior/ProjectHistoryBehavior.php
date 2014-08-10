<?php
/**
 *
 * Behaviour for logging the changes in project components in the SourceKettle system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.Model.Behavior
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class ProjectHistoryBehavior extends ModelBehavior {

/**
 * settings
 *
 * (default value: array())
 *
 * @var array
 * @access public
 */
	public $settings = array();

/**
 * model
 *
 * (default value: null)
 *
 * @var mixed
 * @access public
 */
	public $model = null;

/**
 * _cache
 *
 * @var array
 * @access private
 */
	private $_cache = array();

	// We need to know who's changing things, so we'll be told
	// who's logged in (or fudging things on the command line, or whatever).
	// Default to a user ID of 0 to represent 'system actions'.
	private $_log_user = array('id' => 0, 'name' => '(System action)');

/**
 * this-
 *
 * @var mixed
 * @access public
 */
	public function setup(Model $model, $settings = array()) {
		$this->settings[$model->name] = $settings;
		$this->model = &$model;
	}

	//
	public function setLogUser($task, $user) {
		$this->_log_user = $user;
	}

/**
 * beforeSave function.
 *
 * @access public
 * @param array $options (default: array())
 * @return void
 * @throws
 */
	public function beforeSave(Model $Model) {
		$exception = false;
		$this->prepare($Model);

		$before = $Model->findById($Model->id);
		$this->_cache[$Model->name][$Model->id] = array();

		foreach ($Model->data[$Model->name] as $field => $value) {
			if (isset($before[$Model->name]) && $field != 'modified' && $before[$Model->name][$field] != $value) {
				$this->_cache[$Model->name][$Model->id][$field] = $before[$Model->name][$field];
			}
		}
		return true;
	}

/**
 * afterSave function.
 *
 * @access public
 * @param bool $created (default: false)
 * @return void
 */
	public function afterSave(Model $Model, $created = false) {
		if (!$created) {
			// Some fields have been updated
			foreach ($this->_cache[$Model->name][$Model->id] as $field => $old) {
				$new = $Model->field($field);
				if ($old == $new) {
					continue;
				}
				$Model->Project->ProjectHistory->logC(
					strtolower($Model->name),
					$Model->id,
					$this->getTitleForHistory($Model),
					$field,
					$old,
					$new,
					$this->_log_user['id'],
					$this->_log_user['name']
				);
			}
		} else {
			// We have a new Project => Use '+' to signify creation
			$Model->Project->ProjectHistory->logC(
				strtolower($Model->name),
				$Model->id,
				$this->getTitleForHistory($Model),
				'+',
				null,
				null,
				$this->_log_user['id'],
				$this->_log_user['name']
			);
		}
		return true;
	}

/**
 * beforeDelete function.
 *
 * @access public
 * @param Model $Model
 * @param bool $cascade (default: true)
 * @return void
 */
	public function beforeDelete(Model $Model, $cascade = true) {

		$this->prepare($Model);
		$this->_cache[$Model->name][$Model->id] = $this->getTitleForHistory($Model);
		return true;
	}

/**
 * afterDelete function.
 *
 * @access public
 * @return void
 */
	public function afterDelete(Model $Model) {
		$Model->Project->ProjectHistory->logC(
			strtolower($Model->name),
			$Model->id,
			$this->_cache[$Model->name][$Model->id],
			'-',
			null,
			null,
			$this->_log_user['id'],
			$this->_log_user['name']
		);
		return true;
	}

/**
 * getTitleForHistory function.
 * Designed to be overridden.
 *
 * @access public
 * @return void
 */
	public function getTitleForHistory(Model $Model) {
		if (method_exists($Model, 'getTitleForHistory')) {
			return $Model->getTitleForHistory($Model->id);
		}
		return $Model->field($Model->displayField);
	}

	private function prepare(Model $Model) {
		if (!isset($this->_cache[$Model->name])) {
			$this->_cache[$Model->name] = array();
		}
	}
}
