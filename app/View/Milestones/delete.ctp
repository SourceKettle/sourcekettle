<?php
/**
 *
 * View class for APP/milestones/edit for the DevTrack system
 * Allows a user to edit a task for a project
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2014
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Milestones
 * @since         SourceKettle v 1.2
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
$this->Html->css('milestones.index', null, array ('inline' => false));

?>

<?= $this->Bootstrap->page_header(__("Delete milestone")) ?>

<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>

    <div class="row">
        <div class="well span8 offset1">

			<h4><?=__("Are you sure you want to delete")?> '<?=h($name)?>'?</h4>
        	<?=$this->Form->create('Milestone', array('class' => 'well form-horizontal', 'type' => 'post'))?>

			<?if(count($milestone['Tasks']['open']) > 0 || count($milestone['Tasks']['in_progress']) > 0 || count($milestone['Tasks']['dropped']) > 0){?>
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
			__("I'm sure. Delete that milestone")."!",
			array("style" => "primary", "size" => "large", 'class' => 'deleteButton span7')
		);?>

        <?=$this->Form->end()?>
        </div>
    </div>
</div>

