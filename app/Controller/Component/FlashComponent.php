<?php
/**
 *
 * Flash Component for the SourceKettle system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	SourceKettle Development Team 2012
 * @link		http://github.com/SourceKettle/sourcekettle
 * @package		SourceKettle.Controller.Component
 * @since		SourceKettle v 0.1
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Component', 'Controller');
class FlashComponent extends Component {

	public $components = array('Session');

	public $Controller;

	public $Model;

	public $name;

	protected $_name;

	protected $_id;

/**
 * initialize function.
 *
 * @access public
 * @param Controller $Controller
 * @return void
 */
	public function initialize(Controller $Controller) {
		$this->Controller	= $Controller;
		$this->name			= Inflector::singularize($Controller->name);
		$this->Model		= $this->Controller->{$this->name};
	}

	public function info($message) {
		$this->__flashBoolean($message, true);
	}

	public function message($message) {
		$subject = "<h4 class='alert-heading'>Please Note:</h4>{reason}.";
		$search	= array('{reason}');
		$replace = array($message);
		$this->__flash(str_replace($search, $replace, $subject), 'info');
	}

	public function error($message) {
		$this->__flashBoolean($message, false);
	}

	public function errorReason($reason) {
		$subject = "<h4 class='alert-heading'>The Request could not be completed:</h4>{reason}.";
		$search	= array('{reason}');
		$replace = array($reason);
		$this->__flashBoolean(str_replace($search, $replace, $subject), false);
	}

	public function c($winning = false) {
		return $this->__objectFlash("has been created", "could not be created", $winning);
	}

	public function u($winning = false) {
		return $this->__objectFlash("has been updated", "could not be updated", $winning);
	}

	public function d($winning = false, $objectName = null) {
		if (isset($this->Model->actsAs)) {
			if (in_array('SoftDeletable', $this->Model->actsAs)) {
				$winning = true;
			}
		}
		return $this->__objectFlash("has been deleted", "could not be deleted", $winning, $objectName);
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
	private function __objectFlash($messageA, $messageB, $winning, $objectName = null) {
		$this->setUp();
		$message = ($winning) ? $messageA : $messageB . '. Please try again';
		$objectName = ($objectName == null) ? h($this->_name) : h($objectName);

		$subject = "{class} '<strong>{name}</strong>' {message}.";
		$search	= array('{class}', '{name}', '{message}');
		$replace = array($this->name, $objectName, $message);

		$replaced = str_replace($search, $replace, $subject);

		return $this->__flashBoolean($replaced, $winning);
	}

/**
 * flashBoolean function.
 *
 * @access private
 * @param mixed $message
 * @param bool $winning (default: false)
 * @return void
 */
	private function __flashBoolean($message, $winning = false) {
		$this->__flash($message, ($winning) ? 'success' : 'error');
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
	private function __flash($message, $color) {
		$this->Session->setFlash(__($message), 'default', array(), $color);
	}

/**
 * setUp function.
 *
 * @access private
 * @return void
 */
	public function setUp() {
		// If the model exists in the database, we need to pull out its display field
		if ($this->Model->id) {
			if ($this->Model->actsAs && in_array('SoftDeletable', $this->Model->actsAs)) $this->Model->enableSoftDeletable(false);
			$this->_name = $this->Model->field($this->Model->displayField);
			$this->_id	= $this->Model->id;
			if ($this->Model->actsAs && in_array('SoftDeletable', $this->Model->actsAs)) $this->Model->enableSoftDeletable(true);

		} else {
			// Otherwise, pull it out of the model's data array as the save failed
			// TODO is this a massive hack or the Right Thing? I can't tell at the moment.
			$this->_name = @$this->Model->data[$this->name][$this->Model->displayField];
		}
	}

	public function set($message, $options = array()) {
		$this->message($message);
	}
}

