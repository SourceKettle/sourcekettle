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
                $this->_cache[$field] = array($before[$this->name][$field], $value);
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
            foreach ($this->_cache as $field => $values) {
                $this->Project->logC(strtolower($this->name), $this->id, $field, $values[0], $values[1]);
            }
        } else {
            $this->recursive = -1;
            $new = $this->read();
            unset($new[$this->name]['created']);
            unset($new[$this->name]['modified']);
            $this->Project->logC(strtolower($this->name), $this->id, null, null, $this->getRawSerialisedObject());
        }
        return true;
    }

    /**
     * beforeDelete function.
     *
     * @access public
     * @param bool $cascade (default: true)
     * @return void
     */
    public function beforeDelete($cascade = true) {
        $this->_cache = $this->getRawSerialisedObject();
        return true;
    }

    /**
     * afterDelete function.
     *
     * @access public
     * @return void
     */
    public function afterDelete() {
        $this->Project->logC(strtolower($this->name), $this->id, null, $this->_cache, null);
        return true;
    }

    /**
     * getRawSerialisedObject function.
     *
     * @access private
     * @return void
     */
    private function getRawSerialisedObject() {
        $this->recursive = -1;
        $new = $this->read();

        unset($new[$this->name]['id']);
        unset($new[$this->name]['created']);
        unset($new[$this->name]['modified']);

        return serialize($new[$this->name]);
    }
}
