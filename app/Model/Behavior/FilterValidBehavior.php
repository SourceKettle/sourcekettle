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

		// Special case - zero for 'return anything with this not set'...
		$hasZero = in_array('0', $values, true);

		// Build an SQL query to list ID => name or just an ID list if we have no name field
		$byId = array_filter($values, 'is_numeric');
		$byName = array_filter($values, function($a) {return !is_numeric($a);});

		// If they've specified 'all', shortcut that to an array of all possibilities
		if (in_array('all', $byName)) {
			return array_merge(array(0 => 'None'), $model->find('list'));
		}

		$conditions = array('id' => $byId);
		$fields = array('id');
		$nameField = @$this->settings[$model->name]['nameField'];

		if(isset($nameField) && !empty($nameField)) {
			$conditions = array('OR' => array(
				$conditions,
				array($nameField => $byName)
			));
			$fields[] = $nameField;
		}

		$found = $model->find('list', array('conditions' => $conditions, 'fields' => $fields));

		if ($hasZero) {
			$found = array_merge(array(0 => 'None'), $found);
		}

		return $found;
	}

}
