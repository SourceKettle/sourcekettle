<?php
/**
 *
 * Modal class for APP/times/add for the SourceKettle system
 * Shows a modal box for adding time elements
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Elements.Time
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<div class="modal hide" id="addTimeModal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">x</button>
        <h4><?=__("Log time for this Project")?></h4>
    </div>
    <?= $this->Form->create('Time', array(
		'url' => array(
			'controller' => 'times',
			'action' => 'add',
			'project' => $project['Project']['name']),
			'id' => 'TimeMyaddForm',
			'class' => 'form-horizontal',
			'style' => 'margin-bottom: 0px;')) ?>
    <div class="modal-body">
        <?= $this->element('Time/add') ?>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal">Close</a>
		<input type='submit' value='Log time'/>
    </div>
    <?= $this->Form->end() ?>
</div>
