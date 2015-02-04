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

	<h4><?=__("Are you sure you want to close")?> '<?=h($milestone['Milestone']['subject'])?>'?</h4>
	<?=$this->Form->create('Milestone', array('type' => 'post'))?>

	<?if($milestone['Tasks']['open']['numTasks'] > 0 || $milestone['Tasks']['in progress']['numTasks'] > 0 || $milestone['Tasks']['dropped']['numTasks'] > 0){?>
		<p><strong><?=__("Caution")?>:</strong> <?=__("this milestone has unfinished/dropped tasks")?>!</p>
		<p><?=__("Which milestone (if any) should they be re-assigned to")?>?</p>
		<?=$this->Form->input(
			'new_milestone',
			array('options' => $other_milestones, 'default' => '(no milestone)', 'label' => false)
		)?>
	<?} else {?>
		<p><?=__("Looks like all tasks are finished")?>! <?=__("Great work")?>!</p>
	<?}?>
<?=$this->Bootstrap->button(
	__("I'm sure. Close that milestone")."!",
	array("style" => "primary", "size" => "large", 'class' => 'deleteButton span7')
);?>

<?=$this->Form->end()?>
	</div>
</div>

