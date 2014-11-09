<?php
/**
 *
 * Helper class for Tasks section of the SourceKettle system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	SourceKettle Development Team 2012
 * @link		http://github.com/SourceKettle/sourcekettle
 * @package		SourceKettle.View.Helper
 * @since		SourceKettle v 0.1
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class TaskHelper extends AppHelper {


/**
 * helpers
 *
 * @var string
 * @access public
 */
	public $helpers = array('Html', 'Bootstrap' => array('className' => 'TwitterBootstrap.TwitterBootstrap'));

/**
 * priority function.
 *
 * @access public
 * @param mixed $id
 * @return void
 */
	public function priority($id, $textLabel = true) {
		$label = $textLabel? '<span class="hidden-phone hidden-tablet">'.$this->_View->viewVars['task_priorities'][$id]['label'].'</span> ' : '';
		$icon  = $this->_View->viewVars['task_priorities'][$id]['icon'];
		$class = $this->_View->viewVars['task_priorities'][$id]['class'];
		return $this->Bootstrap->label($label . $this->Bootstrap->icon($icon, "white"), "inverse", array('class' => "taskpriority $class"));
	}

/**
 * status function.
 *
 * @access public
 * @param mixed $id
 * @return void
 */
	public function status($id) {
		return $this->_View->viewVars['task_statuses'][$id]['label'];
	}

/**
 * Get a label to show the status of a task
 * @access public
 * @param mixed $id
 * @return void
 */
	public function statusLabel($id) {
		$label = $this->_View->viewVars['task_statuses'][$id]['label'];
		$class = $this->_View->viewVars['task_statuses'][$id]['class'];
		return $this->Bootstrap->label($label, $class, array ("class" => "taskstatus"));
	}

/**
 * type function.
 *
 * @access public
 * @param mixed $id
 * @return void
 */
	public function type($id) {
		$label = $this->_View->viewVars['task_types'][$id]['label'];
		$class = $this->_View->viewVars['task_types'][$id]['class'];
		return $this->Bootstrap->label($label, $class);
	}
}
