<?php
/**
 *
 * Element for APP/tasks/index for the SourceKettle system
 * Shows a task box for a task
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
if (!isset($draggable)){
    $draggable = false;
}
if(isset($span) && $span){
	$span=" span$span";
} else {
	$span="";
}
$apiUrl = $this->Html->url(array(
  	'controller' => 'tasks',
	'action' => 'update',
	'project' => $project['Project']['name'],
	'api' => true,
));
$url = array('api' => false, 'project' => $task['Project']['name'], 'controller' => 'tasks', 'action' => 'view', $task['Task']['public_id']);
	if($draggable){
		echo "<li class='draggable$span' data-taskid='".h($task['Task']['public_id'])."' data-api-url='$apiUrl'>";
	} else {
		echo "<li class='$span'>";
	}
?>
<div id="task_<?= $task['Task']['public_id'] ?>" 
  class="task-container"
  <?
  // If it's a draggable item in the milestone board, do NOT make the whole thing a click target...
  if(!$draggable){?>
  onclick="location.href='<?= $this->Html->url($url) ?>';"
  <?}?>
  data-taskid="<?= $task['Task']['public_id'] ?>">
    <div class="task">
        <div class="well type_bar_<?= h($task['TaskType']['name']) ?>">
            <div class="row-fluid">
                <div>
                    <div class="span10">
                        <p>
                            <?= $this->Html->link(
								'<strong>#'.h($task['Task']['public_id']).'</strong> - '.h($task['Task']['subject']),
								$url,
								array(
									'escape' => false,
									'title' => '#'.h($task['Task']['public_id']).' - '.h($task['Task']['subject']),
								)
							) ?>
							<?= $this->Html->link($this->Bootstrap->icon("pencil"), array(
								'controller' => 'tasks',
								'action' => 'edit',
								'project' => $task['Project']['name'],
								$task['Task']['public_id'],
							), array('escape' => false)) ?>
                        </p>

                        <?
                        echo $this->Task->statusLabel($task['Task']['task_status_id']);
                        if (isset($task['Milestone']['id'])){
                            echo "<span class='label' title='".__('Milestone: %s', $task['Milestone']['subject'])."'>";
							echo $this->Html->link($this->Bootstrap->icon("road", "white"), array(
								'controller' => 'milestones',
								'action' => 'view',
								'project' => $task['Project']['name'],
								$task['Milestone']['id'],
							), array('escape' => false));
							echo "</span>";
                        }

                        echo $this->Task->priority($task['Task']['task_priority_id']);
                        
						if (!empty($task['DependsOn'])){
                            if (!$task['Task']['dependenciesComplete']){
                                echo "<span class='label label-important' title='Dependencies incomplete'>D</span>";
                            } else {
                                echo "<span class='label label-success' title='Dependencies complete'>D</span>";
                            }
                        }

						// Story points, with +/- buttons
                        ?>
						<span class="btn-group btn-group-storypoints">
						<?=$this->Bootstrap->button("-", array('class' => 'btn-inverse btn-storypoints'))?>
						<?=$this->Bootstrap->button(__("<span class='points'>%d</span> SP", $task['Task']['story_points'] ?: 0), array('class' => 'disabled btn-inverse btn-storypoints'))?>
						<?=$this->Bootstrap->button("+", array('class' => 'btn-inverse btn-storypoints'))?>
						</span>
						
                    </div>
                    <div class="span2 task-lozenge-assignee hidden-phone">
					  <?if(isset($task['Assignee']['email'])){?>
                        <?= $this->Gravatar->image($task['Assignee']['email'], array(), array('alt' => $task['Assignee']['name'])) ?>
					  <?} else {?>
                        <?= $this->Gravatar->image('', array('d' => 'mm'), array('alt' => $task['Assignee']['name'])) ?>
					  <?}?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</li>
