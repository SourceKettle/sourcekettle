<?php
/**
 *
 * Application Model for Project components in the DevTrack system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.Model
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('AppModel', 'Model');

class AppProjectModel extends Model {

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
    public function beforeSave($options = array()) {
        $before = $this->findById($this->id);
        foreach ($this->data[$this->name] as $field => $value) {
            if ($field != 'modified' && $before[$this->name][$field] != $value) {
                $this->_cache[$field] = $before[$this->name][$field];
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
    public function afterSave($created = false) {
        if (!$created) {
            // Some fields have been updated
            foreach ($this->_cache as $field => $value) {
                $this->Project->ProjectHistory->logC(
                    strtolower($this->name),
                    $this->id,
                    $this->getTitleForHistory($this->id),
                    $field,
                    $value,
                    $this->field($field),
                    $this->_auth_user_id,
                    $this->_auth_user_name
                );
            }
        } else {
            // We have a new Project => Use '+' to signify creation
            $this->Project->ProjectHistory->logC(
                strtolower($this->name),
                $this->id,
                $this->getTitleForHistory($this->id),
                '+',
                null,
                null,
                $this->_auth_user_id,
                $this->_auth_user_name
            );
        }
        return true;
    }

    public function beforeDelete($cascade = true) {
        $this->_cache = $this->getTitleForHistory($this->id);
        return true;
    }
    /**
     * afterDelete function.
     *
     * @access public
     * @return void
     */
    public function afterDelete() {
        $this->Project->ProjectHistory->logC(
            strtolower($this->name),
            $this->id,
            $this->_cache,
            '-',
            null,
            null,
            $this->_auth_user_id,
            $this->_auth_user_name
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
    public function getTitleForHistory($id) {
        $this->id = $id;
        return $this->field($this->displayField);
    }

}
