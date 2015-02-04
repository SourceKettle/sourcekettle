<?php
/**
 *
 * View class for APP/milestones/edit for the SourceKettle system
 * Allows a user to edit a task for a project
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	 SourceKettle Development Team 2014
 * @link		  http://github.com/SourceKettle/sourcekettle
 * @package	   SourceKettle.View.Milestones
 * @since		 SourceKettle v 1.2
 * @license	   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
$this->Html->css('milestones.index', null, array ('inline' => false));

?>

<div class="row-fluid">
	<div class="well span8 offset2">

	<h4><?=__("Are you sure you want to delete")?> '<?=h($milestone['Milestone']['subject'])?>'? <?=__("This action is irreversible!")?></h4>
	<?=$this->Form->create('Milestone', array('type' => 'post'))?>

	<?if(count($milestone['Tasks']['open']) > 0 || count($milestone['Tasks']['in_progress']) > 0 || count($milestone['Tasks']['dropped']) > 0){?>
		<p><strong><?=__("Caution")?>:</strong> <?=__("this milestone has attached tasks")?>!</p>
		<p><?=__("Which milestone (if any) should they be re-assigned to")?>?</p>
		<?=$this->Form->input(
			'new_milestone',
			array('options' => $other_milestones, 'default' => '(no milestone)', 'label' => false)
		)?>
	<?} else {?>
		<p><?=__("There are no tasks associated with this milestone.")?></p>
	<?}?>
<?=$this->Bootstrap->button(
	__("I'm sure. Delete that milestone")."!",
	array("style" => "danger", "size" => "large", 'class' => 'deleteButton span8')
);?>

<?=$this->Form->end()?>
	</div>
</div>

