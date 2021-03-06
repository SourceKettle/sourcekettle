<?php
/**
 *
 * View class for APP/milestones/edit for the SourceKettle system
 * Allows a user to edit a task for a project
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	 SourceKettle Development Team 2012
 * @link		  http://github.com/SourceKettle/sourcekettle
 * @package	   SourceKettle.View.Milestones
 * @since		 SourceKettle v 0.1
 * @license	   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
$this->Html->css('milestones.index', null, array ('inline' => false));

?>

<div class="row-fluid">
	<div class="well span8 offset2">

		<h4>Are you sure you want to re-open '<?=h($name)?>'?</h4>
		<?=$this->Form->create('Milestone', array('class' => 'form-horizontal', 'type' => 'post'))?>

	<?=$this->Bootstrap->button(
		"I'm sure. Re-open it at once!",
		array("style" => "primary", "size" => "large", 'class' => 'deleteButton span11')
	);?>

	<?=$this->Form->end()?>
	</div>
</div>
