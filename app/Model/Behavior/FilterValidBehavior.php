<?php
/**
 *
 * Behaviour for filtering a list of IDs and unique text values to retain only the valid ones.
 * Used for validating parameters passed to a controller action.
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.Model.Behavior
 * @since         SourceKettle v 1.2
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class FilterValidBehavior extends ModelBehavior {

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

	public function setup(Model $model, $settings = array()) {
		$this->settings[$model->name] = $settings;
		$this->model = &$model;
	}

	public function filterValid(Model $model, $values = array()) {

		// Build an SQL query to list ID => name or just an ID list if we have no name field
		$by_id = array_filter($values, 'is_numeric');
		$by_name = array_filter($values, function($a) {return !is_numeric($a);});

		// If they've specified 'all', shortcut that to an array of all possibilities
		if (in_array('all', $by_name)) {
			return $model->find('list');
		}

		$conditions = array('id' => $by_id);
		$fields = array('id');

		$nameField = @$this->settings[$model->name]['nameField'];

		if(isset($nameField) && !empty($nameField)) {
			$conditions = array('OR' => array(
				$conditions,
				array($nameField => $by_name)
			));
			$fields[] = $nameField;
		}

		return $model->find('list', array('conditions' => $conditions, 'fields' => $fields));
	}

}
