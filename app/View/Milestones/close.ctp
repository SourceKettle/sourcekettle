<?php
/**
 *
 * View class for APP/milestones/edit for the DevTrack system
 * Allows a user to edit a task for a project
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/SourceKettle/devtrack
 * @package       DevTrack.View.Milestones
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
$this->Html->script('bootstrap-datepicker', array('block' => 'scriptBottom'));
$this->Html->scriptBlock("$('.dp1').datepicker()", array('inline' => false));
$this->Html->css('datepicker', null, array ('inline' => false));
$this->Html->css('milestones.index', null, array ('inline' => false));

?>

<?= $this->Bootstrap->page_header("Close milestone") ?>

<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>

    <div class="row">
        <div class="well span8 offset1">

			<h4>Are you sure you want to close '<?=h($name)?>'?</h4>
        	<?=$this->Form->create('Milestone', array('class' => 'well form-horizontal', 'type' => 'post'))?>

			<?if(count($milestone['Tasks']['open']) > 0 || count($milestone['Tasks']['in_progress']) > 0 || count($milestone['Tasks']['dropped']) > 0){?>
				<p><strong>Caution:</strong> this milestone has unfinished/dropped tasks!</p>
				<p>Which milestone (if any) should they be re-assigned to?</p>
                <?/*=$this->Bootstrap->button_dropdown(
					'(no milestone)',
					array(
						'name' => 'new_milestone',
						'links' => $other_milestones,
					)
				)*/?>
				<?=$this->Form->input(
					'new_milestone',
					array('options' => $other_milestones, 'default' => '(no milestone)', 'label' => false)
				)?>
			<?} else {?>
				<p>Looks like all tasks are finished! Great work!</p>
			<?}?>
        <?=$this->Bootstrap->button(
			"I'm sure. Close that milestone!",
			array("style" => "primary", "size" => "large", 'class' => 'deleteButton span7')
		);?>

        <?=$this->Form->end()?>
        </div>
    </div>
</div>
