<div class="span<?=$span?><?= (@$grey) ? ' greyOut':'' ?>">
    <h3><?= __("Tasks") ?></h3>
    <hr />
    <div class="row-fluid">
        <div class="span6">
            <ul id="taskcounts" class="unstyled">
                <li class="open-tasks" data-numtasks="<?=h($numberOfOpenTasks)?>" data-taskstatus="<?=h(__('Open'))?>">
                  <?= $this->Html->link(
                   "$numberOfOpenTasks - ".__("open tasks"),
                   array(
                     'project'    => $project['Project']['name'],
                     'controller' => 'tasks',
                     'action'     => 'index',
                     '?' => array('statuses' => 'open'),
                   ))?>
                </li>

                <li class="in-progress-tasks" data-numtasks="<?=h($numberOfInProgressTasks)?>" data-taskstatus="<?=h(__('In progress'))?>">
                  <?= $this->Html->link(
                   "$numberOfInProgressTasks - ".__("tasks in progress"),
                   array(
                     'project'    => $project['Project']['name'],
                     'controller' => 'tasks',
                     'action'     => 'index',
                     '?' => array('statuses' => 'in progress'),
                   ))?>
                </li>

                <li class="closed-tasks" data-numtasks="<?=h($numberOfClosedTasks)?>" data-taskstatus="<?=h(__('Finished'))?>">
                  <?= $this->Html->link(
                    "$numberOfClosedTasks - ".__("finished tasks"),
                   array(
                     'project'    => $project['Project']['name'],
                     'controller' => 'tasks',
                     'action'     => 'index',
                     '?' => array('statuses' => 'closed,resolved'),
                  ))?>
                </li>

                <li class="dropped-tasks" data-numtasks="<?=h($numberOfDroppedTasks)?>" data-taskstatus="<?=h(__('Dropped'))?>">
                  <?= $this->Html->link(
                    "$numberOfDroppedTasks - ".__("dropped tasks"),
                   array(
                     'project'    => $project['Project']['name'],
                     'controller' => 'tasks',
                     'action'     => 'index',
                     '?' => array('statuses' => 'dropped'),
                  ))?>
                </li>

                <li class="total-tasks">
                  <?=$this->Html->link(
                    "$numberOfTasks - ".__("total tasks"),
                   array(
                     'project'    => $project['Project']['name'],
                     'controller' => 'tasks',
                     'action'     => 'index'
                    ))?>
                </li>

                <li><?= h($percentOfTasks) ?>% <?= __("complete") ?></li>
            </ul>
        </div>
        <div class="span6">
 		  <div class="well" id="piechart">
 	      </div>
        </div>
    </div>
</div>

