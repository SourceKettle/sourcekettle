<?php
/**
 *
 * Behaviour for components in the DevTrack system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.Model.Behavior
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class ProjectDeletableBehavior extends ModelBehavior {

    var $settings = array();

    var $model = null;

    var $ignore = array('ProjectHistory');

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
        $objects = $this->preDeleteR($Model, array('id' => $Model->id));
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
     * preDeleteR function.
     *
     * @access private
     * @param mixed $Model
     * @param array $conditions (default: array())
     * @return void
     */
    private function preDeleteR($Model, $conditions = array()) {
        $Model->recursive = -1;

        $objects = array($Model->name => $Model->find('list', array('conditions' => $conditions)));
        $_list = array_values($Model->find('list', array('conditions' => $conditions, 'fields' => array('id'))));
        foreach ($Model->hasMany as $key => $value) {
            if ($value['dependent']) {
                $_objects = $this->preDeleteR($Model->{$key}, array($value['foreignKey'] => $_list));
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
