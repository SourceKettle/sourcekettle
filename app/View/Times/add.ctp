<?php
/**
 *
 * View class for APP/times/add for the SourceKettle system
 * Allows users to allocate time to a project
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	 SourceKettle Development Team 2012
 * @link		  http://github.com/SourceKettle/sourcekettle
 * @package	   SourceKettle.View.Times
 * @since		 SourceKettle v 0.1
 * @license	   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>

<div class="row-fluid">
<?= $this->element('Time/topbar_history') ?>
</div>
<div class="row-fluid">
	<div class="well times form span8 offset2">
		<?= $this->Form->create('Time', array('project' => $project['Project']['id'], 'class' => 'form-horizontal')) ?>
		<?= $this->element('Time/add', array('span' => 12)) ?>
		<?= $this->Bootstrap->button("Submit", array("style" => "primary", 'class' => 'controls')) ?>
		<?= $this->Form->end() ?>
	</div>
</div>
