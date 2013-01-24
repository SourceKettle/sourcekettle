<?php
/**
 *
 * Flash Component for the DevTrack system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/SourceKettle/devtrack
 * @package       DevTrack.Controller.Component
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Component', 'Controller');
class FlashComponent extends Component {

    var $components = array('Session');

    var $Controller;
    var $Model;
    var $name;

    var $_name;
    var $_id;

    /**
     * initialize function.
     *
     * @access public
     * @param Controller $Controller
     * @return void
     */
    public function initialize(Controller $Controller) {
        $this->Controller = $Controller;
        $this->name       = Inflector::singularize($Controller->name);
        $this->Model      = $this->Controller->{$this->name};
    }

    public function info($message) {
        $this->flashBoolean($message, true);
    }
    public function message($message) {
        $subject = "<h4 class='alert-heading'>Please Note:</h4>{reason}.";
        $search  = array('{reason}');
        $replace = array($message);
        $this->flash(str_replace($search, $replace, $subject), 'info');
    }
    public function error($message) {
        $this->flashBoolean($message, false);
    }
    public function errorReason($reason) {
        $subject = "<h4 class='alert-heading'>The Request could not be completed</h4>{reason}.";
        $search  = array('{reason}');
        $replace = array($reason);
        $this->flashBoolean(str_replace($search, $replace, $subject), false);
    }

    public function C($winning = false) {
        return $this->objectFlash("has been created", "could not be created", $winning);
    }
    //public function R($winning = false) {
    //    return $this->objectFlash("", "", $winning);
    //}
    public function U($winning = false) {
        return $this->objectFlash("has been updated", "could not be updated", $winning);
    }
    public function D($winning = false) {
        if (in_array('SoftDeletable', $this->Model->actsAs)) $winning = true;
        return $this->objectFlash("has been deleted", "could not be deleted", $winning);
    }

    /**
     * objectFlash function.
     *
     * @access private
     * @param mixed $messageA
     * @param mixed $messageB
     * @param mixed $winning
     * @return void
     */
    private function objectFlash($messageA, $messageB, $winning) {
        $this->setUp();
        $message = ($winning) ? $messageA : $messageB.'. Please try again';

        $subject = "{class} '<strong>{name}</strong>' {message}.";
        $search  = array('{class}', '{name}', '{message}');
        $replace = array($this->name, $this->_name, $message);

        $replaced = str_replace($search, $replace, $subject);

        return $this->flashBoolean($replaced, $winning);
    }

    /**
     * flashBoolean function.
     *
     * @access private
     * @param mixed $message
     * @param bool $winning (default: false)
     * @return void
     */
    private function flashBoolean($message, $winning = false) {
        $this->flash($message, ($winning) ? 'success' : 'error');
        return $winning;
    }

    /**
     * flash function.
     *
     * @access private
     * @param mixed $message
     * @param mixed $color
     * @return void
     */
    private function flash($message, $color) {
        $this->Session->setFlash(__($message), 'default', array(), $color);
    }

    /**
     * setUp function.
     *
     * @access private
     * @return void
     */
    public function setUp() {
        if ($this->Model->id) {
            if ($this->Model->actsAs && in_array('SoftDeletable', $this->Model->actsAs)) $this->Model->enableSoftDeletable(false);
            $this->_name = $this->Model->field($this->Model->displayField);
            $this->_id   = $this->Model->id;
            if ($this->Model->actsAs && in_array('SoftDeletable', $this->Model->actsAs)) $this->Model->enableSoftDeletable(true);
        }
    }
}

