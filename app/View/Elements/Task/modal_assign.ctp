<?php
/**
 *
 * Modal class for APP/tasks/add for the SourceKettle system
 * Shows a modal box for assigning users
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Elements.Task
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<div class="modal hide" id="assignModal">
    <div class="modal-body">
        <button type="button" class="close" data-dismiss="modal">x</button>
        <p>
            <?= __('To assign this task to a user on this project, enter their name in the box below and select "Assign"') ?>
        </p>
        <?php
            echo $this->Form->create('Assignee', array('class' => 'form-inline', 'url' => array('controller' => 'tasks', 'action' => 'assign', 'project' => $project['Project']['name'], $task['Task']['id'])));
			$assignee_id = isset($task['Task']['assignee_id'])? $task['Task']['assignee_id']: 0;
            echo $this->Form->input('id', array(
                'options' => $collaborators,
                'empty' => false,
                //'selected' => "$user_name [$user_email]",
				'selected' => $assignee_id,
                'label' => false,
                'id' => 'appendedInputButton',
                'class' => 'span5',
            ));
        ?>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal"><?= __('Close') ?></a>
        <?= $this->Bootstrap->button(__('Assign'), array("style" => "success")) ?>
    </div>
    <?= $this->Form->end() ?>
</div>
