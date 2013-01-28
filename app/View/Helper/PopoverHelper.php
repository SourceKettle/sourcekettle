<?php
/**
 *
 * Helper class for the Popover Plugin for the DevTrack system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	DevTrack Development Team 2012
 * @link		http://github.com/SourceKettle/devtrack
 * @package		DevTrack.View.Helper
 * @since		DevTrack v 0.1
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class PopoverHelper extends AppHelper {

	var $hasBeenCalled = false;

	var $helpers = array('Html');

	/*
	 * popover
	 * Wrapper for a Html link that will render a popover
	 *
	 * @param $a_text string the text to display on page
	 * @param $p_title string the title for the popover
	 * @param $p_content string the concent for the popover
	 */
	public function popover($a_text, $p_title, $p_content) {
		$this->hasBeenCalled = true;
		return $this->Html->link($a_text, '#', array('class' => 'popover-devtrack', 'rel' => 'popover', 'data-content' => $p_content, 'data-original-title' => $p_title));
	}

	/*
	 * requirements
	 * Will return the requirements for popover
	 *
	 */
	public function requirements() {
		if ($this->hasBeenCalled) {
			return $this->Html->script(array('bootstrap-tooltip', 'bootstrap-popover')) . $this->Html->scriptBlock("$('.popover-devtrack').popover()");
		}
	}

}
