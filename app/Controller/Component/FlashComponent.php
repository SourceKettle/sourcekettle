<?php
/**
 *
 * Flash Component for the DevTrack system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
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

        return $this->flash($replaced, $winning);
    }

    /**
     * flash function.
     *
     * @access private
     * @param mixed $message
     * @param bool $winning (default: false)
     * @return void
     */
    private function flash($message, $winning = false) {
        $this->Session->setFlash(__($message), 'default', array(), ($winning) ? 'success' : 'error');
        return $winning;
    }

    /**
     * setUp function.
     *
     * @access private
     * @return void
     */
    private function setUp() {
        $this->_name = $this->Model->field($this->Model->displayField);
        $this->_id   = $this->Model->id;
    }
}

