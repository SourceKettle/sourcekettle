<?php
/**
 *
 * Element for displaying modifications to Tasks in the SourceKettle system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Element.Task
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

    $field = h($change['ProjectHistory']['row_field']);
    $old = h($change['ProjectHistory']['row_field_old']);
    $new = h($change['ProjectHistory']['row_field_new']);

    switch ( $field ) {
        case 'task_type_id':
            $pop_over = 'type: '.$this->Task->typeDropdownButton(null, $old).' &rarr; '.$this->Task->typeDropdownButton(null, $new);
            break;
        case 'task_priority_id':
            $pop_over = 'priority: '.$this->Task->priorityDropdownButton(null, $old).' &rarr; '.$this->Task->priorityDropdownButton(null, $new);
            break;
        case 'assignee_id':
            $old = ($old) ? $this->Gravatar->image($change_users[$old][1], array('d' => 'mm', 's' => 24)).' '.h($change_users[$old][0]) : '<small>No-one assigned</small>';
            $new = ($new) ? $this->Gravatar->image($change_users[$new][1], array('d' => 'mm', 's' => 24)).' '.h($change_users[$new][0]) : '<small>No-one assigned</small>';
            $pop_over = $this->Popover->popover(
                'assignee',
                'Tasks \'Assignee\' changed',
                "<h4 class='hr-h4'>Before</h4>
                 <p>${old}</p>
                 <hr class='hr-popover'>
                 <h4 class='hr-h4'>After</h4>
                 <p>${new}</p>"
            );
            break;
        case 'task_status_id':
            $pop_over = 'status: '.$this->Task->statusDropdownButton(null, $old).' &rarr; '.$this->Task->statusDropdownButton(null, $new);
            break;
        default:
            $old = ($old) ? $old : '<small>empty</small>';
            $new = ($new) ? $new : '<small>empty</small>';
            $pop_over = $this->Popover->popover(
                $field,
                'Tasks \''.$field.'\' changed',
                "<h4 class='hr-h4'>Before</h4>
                 <hr class='hr-popover'>
                 <p>${old}</p>

                 <h4 class='hr-h4'>After</h4>
                 <hr class='hr-popover'>
                 <p>${new}</p>"
            );
            break;
    }
?>
<div class="row-fluid">

    <div class="span1"></div>
    <div class="span11">
        <div class="colchange">
            <p>
                <?= $this->Bootstrap->label($this->Bootstrap->icon('pencil', 'white').' Update') ?>
                <small>
                    <?= $this->Html->link($change['User']['name'], array('controller' => 'users', 'action' => 'view', $change['User']['id'])) ?>
                    <?= __('updated the task\'s') ?>
                    <?= $pop_over ?>
                    <span class="pull-right"><?= $this->Time->timeAgoInWords($change['ProjectHistory']['created']) ?></span>
                </small>
            </p>
        </div>
    </div>

</div>
