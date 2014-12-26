<?php
/**
 *
 * Helper class for the Popover Plugin for the SourceKettle system
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

class PopoverHelper extends AppHelper {

	private $__hasBeenCalled = false;

	public $helpers = array('Html');

/**
 * popover
 * Wrapper for a Html link that will render a popover
 *
 * @param $aText string the text to display on page
 * @param $pTitle string the title for the popover
 * @param $pContent string the concent for the popover
 */
	public function popover($aText, $pTitle, $pContent) {
		$this->__hasBeenCalled = true;
		return $this->Html->link($aText, '#', array('class' => 'popover-sourcekettle', 'rel' => 'popover', 'data-content' => $pContent, 'data-original-title' => $pTitle));
	}

/**
 * requirements
 * Will return the requirements for popover
 *
 */
	public function requirements() {
		if ($this->__hasBeenCalled) {
			return $this->Html->script(array('/bootstrap-tooltip/bootstrap-tooltip', '/bootstrap-popover/bootstrap-popover')) . $this->Html->scriptBlock("$('.popover-sourcekettle').popover()");
		}
	}

}
