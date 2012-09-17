<?php
/**
 *
 * Behaviour for logging the changes in project components in the DevTrack system
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

class ProjectHistoryBehavior extends ModelBehavior {

    /**
     * settings
     *
     * (default value: array())
     *
     * @var array
     * @access public
     */
    var $settings = array();

    /**
     * model
     *
     * (default value: null)
     *
     * @var mixed
     * @access public
     */
    var $model = null;

    public function setup(Model $model, $settings = array()) {
        /**
         * this-
         *
         * @var mixed
         * @access public
         */
        $this->settings[$model->name] = $settings;
        $this->model = &$model;
    }


    /**
     * _cache
     *
     * @var array
     * @access private
     */
    private $_cache = array();

    /**
     * beforeSave function.
     *
     * @access public
     * @param array $options (default: array())
     * @return void
     */
    public function beforeSave(Model $Model) {
        $exception = false;

        // Lock out those who aren't allowed to write
        if ( $Model->name == 'Collaborator' && !$Model->findByProjectId($Model->Project->id) ) {
            $exception = true;
        }

        if ( !$exception && !$Model->Project->hasWrite($Model->_auth_user_id) ) {
            throw new ForbiddenException(__('You do not have permissions to write to this project'));
        }

        $before = $Model->findById($Model->id);
        foreach ($Model->data[$Model->name] as $field => $value) {
            if ($field != 'modified' && $before[$Model->name][$field] != $value) {
                $this->_cache[$field] = $before[$Model->name][$field];
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
            foreach ($this->_cache as $field => $value) {
                $Model->Project->ProjectHistory->logC(
                    strtolower($Model->name),
                    $Model->id,
                    $this->getTitleForHistory($Model),
                    $field,
                    $value,
                    $Model->field($field),
                    $Model->_auth_user_id,
                    $Model->_auth_user_name
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
                $Model->_auth_user_id,
                $Model->_auth_user_name
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
        $this->_cache = $this->getTitleForHistory($Model);
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
            $this->_cache,
            '-',
            null,
            null,
            $Model->_auth_user_id,
            $Model->_auth_user_name
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
    # @override
    public function getTitleForHistory(Model $Model) {
        if (method_exists($Model, 'getTitleForHistory')) {
            return $Model->getTitleForHistory($Model->id);
        }
        return $Model->field($Model->displayField);
    }

}
