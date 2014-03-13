<?php
/* SVN FILE: $Id: soft_deletable.php 38 2007-11-26 19:36:27Z mgiglesias $ */

/**
 * SoftDeletable Behavior class file.
 *
 * @filesource
 * @author Mariano Iglesias
 * @link http://cake-syrup.sourceforge.net/ingredients/soft-deletable-behavior/
 * @version	$Revision: 38 $
 * @license	http://www.opensource.org/licenses/mit-license.php The MIT License
 * @package app
 * @subpackage app.models.behaviors
 */

/**
 * Model behavior to support soft deleting records.
 *
 * @package app
 * @subpackage app.models.behaviors
 */
class SoftDeletableBehavior extends ModelBehavior {

/**
 * Contain settings indexed by model name.
 *
 * @var array
 * @access private
 */
	private $__settings = array();

/**
 * Initiate behaviour for the model using settings.
 *
 * @param object $Model Model using the behaviour
 * @param array $settings Settings to override for model.
 * @access public
 */
	public function setup(Model $Model, $settings = array()) {
		$default = array('field' => 'deleted', 'field_date' => 'deleted_date', 'delete' => true, 'find' => true);

		if (!isset($this->__settings[$Model->alias])) {
			$this->__settings[$Model->alias] = $default;
		}

		$this->__settings[$Model->alias] = am($this->__settings[$Model->alias], (is_array($settings))?$settings:array());
	}

/**
 * Run before a model is deleted, used to do a soft delete when needed.
 *
 * @param object $Model Model about to be deleted
 * @param boolean $cascade If true records that depend on this record will also be deleted
 * @return boolean Set to true to continue with delete, false otherwise
 * @access public
 */
	public function beforeDelete(Model $Model, $cascade = true) {
		if ($this->__settings[$Model->alias]['delete'] && $Model->hasField($this->__settings[$Model->alias]['field'])) {
			$attributes = $this->__settings[$Model->alias];
			$id = $Model->id;

			$data = array($Model->alias => array(
				$attributes['field'] => 1
			));

			if (isset($attributes['field_date']) && $Model->hasField($attributes['field_date'])) {
				$data[$Model->alias][$attributes['field_date']] = date('Y-m-d H:i:s');
			}

			foreach(am(array_keys($data[$Model->alias]), array('field', 'field_date', 'find', 'delete')) as $field) {
				unset($attributes[$field]);
			}

			if (!empty($attributes)) {
				$data[$Model->alias] = am($data[$Model->alias], $attributes);
			}

			$Model->id = $id;
			$deleted = $Model->save($data, false, array_keys($data[$Model->alias]));

			if ($deleted && $cascade) {
				$Model->beforeDelete($Model, $cascade); // Shonk
				$Model->__deleteDependent($id, $cascade);
				$Model->__deleteLinks($id);
			}

			return false;
		}

		return true;
	}

/**
 * Permanently deletes a record.
 *
 * @param object $Model Model from where the method is being executed.
 * @param mixed $id ID of the soft-deleted record.
 * @param boolean $cascade Also delete dependent records
 * @return boolean Result of the operation.
 * @access public
 */
	public function hardDelete(Model $Model, $id, $cascade = true) {
		$onFind = $this->__settings[$Model->alias]['find'];
		$onDelete = $this->__settings[$Model->alias]['delete'];
		$this->enableSoftDeletable($Model, false);

		$deleted = $Model->del($id, $cascade);

		$this->enableSoftDeletable($Model, 'delete', $onDelete);
		$this->enableSoftDeletable($Model, 'find', $onFind);

		return $deleted;
	}

/**
 * Permanently deletes all records that were soft deleted.
 *
 * @param object $Model Model from where the method is being executed.
 * @param boolean $cascade Also delete dependent records
 * @return boolean Result of the operation.
 * @access public
 */
	public function purge(Model $Model, $cascade = true) {
		$purged = false;

		if ($Model->hasField($this->__settings[$Model->alias]['field'])) {
			$onFind = $this->__settings[$Model->alias]['find'];
			$onDelete = $this->__settings[$Model->alias]['delete'];
			$this->enableSoftDeletable($Model, false);

			$purged = $Model->deleteAll(array($this->__settings[$Model->alias]['field'] => '1'), $cascade);

			$this->enableSoftDeletable($Model, 'delete', $onDelete);
			$this->enableSoftDeletable($Model, 'find', $onFind);
		}

		return $purged;
	}

/**
 * Restores a soft deleted record, and optionally change other fields.
 *
 * @param object $Model Model from where the method is being executed.
 * @param mixed $id ID of the soft-deleted record.
 * @param $attributes Other fields to change (in the form of field => value)
 * @return boolean Result of the operation.
 * @access public
 */
	public function undelete(Model $Model, $id = null, $attributes = array()) {
		if ($Model->hasField($this->__settings[$Model->alias]['field'])) {
			if (empty($id)) {
				$id = $Model->id;
			}

			$data = array($Model->alias => array(
				$Model->primaryKey => $id,
				$this->__settings[$Model->alias]['field'] => '0'
			));

			if (isset($this->__settings[$Model->alias]['field_date']) && $Model->hasField($this->__settings[$Model->alias]['field_date'])) {
				$data[$Model->alias][$this->__settings[$Model->alias]['field_date']] = null;
			}

			if (!empty($attributes)) {
				$data[$Model->alias] = am($data[$Model->alias], $attributes);
			}

			$onFind = $this->__settings[$Model->alias]['find'];
			$onDelete = $this->__settings[$Model->alias]['delete'];
			$this->enableSoftDeletable($Model, false);

			$Model->id = $id;
			$result = $Model->save($data, false, array_keys($data[$Model->alias]));

			$this->enableSoftDeletable($Model, 'find', $onFind);
			$this->enableSoftDeletable($Model, 'delete', $onDelete);

			return ($result !== false);
		}

		return false;
	}

/**
 * Set if the beforeFind() or beforeDelete() should be overriden for specific model.
 *
 * @param object $Model Model about to be deleted.
 * @param mixed $methods If string, method (find / delete) to enable on, if array array of method names, if boolean, enable it for find method
 * @param boolean $enable If specified method should be overriden.
 * @access public
 */
	public function enableSoftDeletable(Model $Model, $methods, $enable = true) {
		if (is_bool($methods)) {
			$enable = $methods;
			$methods = array('find', 'delete');
		}

		if (!is_array($methods)) {
			$methods = array($methods);
		}

		foreach($methods as $method) {
			$this->__settings[$Model->alias][$method] = $enable;
		}
	}

/**
 * Run before a model is about to be find, used only fetch for non-deleted records.
 *
 * @param object $Model Model about to be deleted.
 * @param array $queryData Data used to execute this query, i.e. conditions, order, etc.
 * @return mixed Set to false to abort find operation, or return an array with data used to execute query
 * @access public
 */
	public function beforeFind(Model $Model, $queryData) {
		if ($this->__settings[$Model->alias]['find'] && $Model->hasField($this->__settings[$Model->alias]['field'])) {
			$Db =& ConnectionManager::getDataSource($Model->useDbConfig);
			$include = false;

			if (!empty($queryData['conditions']) && is_string($queryData['conditions'])) {
				$include = true;

				$fields = array(
					$Db->name($Model->alias) . '.' . $Db->name($this->__settings[$Model->alias]['field']),
					$Db->name($this->__settings[$Model->alias]['field']),
					$Model->alias . '.' . $this->__settings[$Model->alias]['field'],
					$this->__settings[$Model->alias]['field']
				);

				foreach($fields as $field) {
					if (preg_match('/^' . preg_quote($field) . '[\s=!]+/i', $queryData['conditions']) || preg_match('/\\x20+' . preg_quote($field) . '[\s=!]+/i', $queryData['conditions'])) {
						$include = false;
						break;
					}
				}
			} else if (empty($queryData['conditions']) || (!in_array($this->__settings[$Model->alias]['field'], array_keys($queryData['conditions'])) && !in_array($Model->alias . '.' . $this->__settings[$Model->alias]['field'], array_keys($queryData['conditions'])))) {
				$include = true;
			}

			if ($include) {
				if (empty($queryData['conditions'])) {
					$queryData['conditions'] = array();
				}

				if (is_string($queryData['conditions'])) {
					$queryData['conditions'] = $Db->name($Model->alias) . '.' . $Db->name($this->__settings[$Model->alias]['field']) . '!= 1 AND ' . $queryData['conditions'];
				} else {
					$queryData['conditions'][$Model->alias . '.' . $this->__settings[$Model->alias]['field']] = '!= 1';
				}
			}
		}

		return $queryData;
	}

/**
 * Run before a model is saved, used to disable beforeFind() override.
 *
 * @param object $Model Model about to be saved.
 * @return boolean True if the operation should continue, false if it should abort
 * @access public
 */
	public function beforeSave(Model $Model) {
		if ($this->__settings[$Model->alias]['find']) {
			if (!isset($this->__backAttributes)) {
				$this->__backAttributes = array($Model->alias => array());
			} else if (!isset($this->__backAttributes[$Model->alias])) {
				$this->__backAttributes[$Model->alias] = array();
			}

			$this->__backAttributes[$Model->alias]['find'] = $this->__settings[$Model->alias]['find'];
			$this->__backAttributes[$Model->alias]['delete'] = $this->__settings[$Model->alias]['delete'];
			$this->enableSoftDeletable($Model, false);
		}

		return true;
	}

/**
 * Run after a model has been saved, used to enable beforeFind() override.
 *
 * @param object $Model Model just saved.
 * @param boolean $created True if this save created a new record
 * @access public
 */
	public function afterSave(Model $Model, $created) {
		if (isset($this->__backAttributes[$Model->alias]['find'])) {
			$this->enableSoftDeletable($Model, 'find', $this->__backAttributes[$Model->alias]['find']);
			$this->enableSoftDeletable($Model, 'delete', $this->__backAttributes[$Model->alias]['delete']);
			unset($this->__backAttributes[$Model->alias]['find']);
			unset($this->__backAttributes[$Model->alias]['delete']);
		}
	}
}
?>
