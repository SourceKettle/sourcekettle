<?php
/**
 *
 * Behaviour for components in the SourceKettle system
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

class ProjectDeletableBehavior extends ModelBehavior {

	public $settings = array();

	public $model = null;

	public $ignore = array('ProjectHistory');

	public function setup(Model $model, $settings = array()) {
		$this->settings[$model->name] = $settings;
		$this->model = &$model;
	}

/**
 * preDelete function.
 *
 * @access public
 * @param Model $Model
 * @return void
 */
	public function preDelete(Model $Model) {
		$objects = $this->__preDeleteR($Model, array('id' => $Model->id));
		$ignore = $this->ignore;
		$ignore[] = $Model->name;
		foreach ($ignore as $i) {
			if (isset($objects[$i])) {
				unset($objects[$i]);
			}
		}
		return $objects;
	}

/**
 * __preDeleteR function.
 *
 * @access private
 * @param mixed $Model
 * @param array $conditions (default: array())
 * @return void
 */
	private function __preDeleteR($Model, $conditions = array()) {
		$Model->recursive = -1;

		$objects = array($Model->name => $Model->find('list', array('conditions' => $conditions)));
		$_list = array_values($Model->find('list', array('conditions' => $conditions, 'fields' => array('id'))));
		foreach ($Model->hasMany as $key => $value) {
			if ($value['dependent']) {
				$_objects = $this->__preDeleteR($Model->{$key}, array($value['foreignKey'] => $_list));
				foreach ($_objects as $_k => $_v) {
					if (isset($objects[$_k])) {
						$objects[$_k] = array_merge($objects[$_k], $_objects[$_k]);
					} else {
						$objects[$_k] = $_objects[$_k];
					}
				}
			}
		}
		return $objects;
	}

}
