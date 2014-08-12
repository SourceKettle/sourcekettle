<?php
/**
 *
 * View for the application dashboard
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Dashboard
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

  echo $this->Bootstrap->page_header(__("Dashboard") . " <small>" . __("welcome") . " " . h(strtolower($current_user['name'])) . "</small>");

  /*if ($DEBUG) {
    echo $this->element('beta_warning'); // TODO show this for dev versions
  }*/
  $this->Html->css('tasks.index', null, array ('inline' => false));
?>


<div class="row">
    <div class="span6">
      <h3>Assigned tasks</h3>
      <? if (!empty($tasks)){
	    echo "<ul class='sprintboard-droplist'>";
        foreach ($tasks as $task){
          echo $this->element('Task/lozenge', array('task' => $task));
        }
		echo "</ul>";
      } else {
        ?><div class='alert alert-success'>You have no assigned tasks! Why don't you assign yourself one?</div><?
      }?>
    </div>
    <div class="span6">
        <h3>Most recent projects</h3>
        <div class='row'>
          <?
             $options = array(
                'left' => array(
                    array(
                        array(
                            'text' => __("View all your projects"),
                            'url' => array(
                                'action' => '.',
                                'controller' => 'projects',
                            ),
                        ),
                    ),
                ),
                'right' => array(
                    array(
                        array(
                            'text' => __("New Project"),
                            'url' => array(
                                'action' => 'add',
                                'controller' => 'projects',
                            ),
                            'props' => array('class' => 'btn-primary'),
                        ),
                    ),
                ),
            );
            echo $this->element('Topbar/button', array('options' => $options, 'span' => 4));

          ?>
        </div>

        <? if (!empty($projects)){
          echo $this->Element("Project/list", array('projects' => $projects, 'nospan' => true));
        } else {
          echo $this->element('Project/noprojectsalert');
        }?>
    </div>
</div>

<div class='row'>
    <div class='span10'>
      <h3>Recent events</h3>
        <? if (!empty($history)){
          ?><div class='alert alert-info'><strong>In progress!</strong> Source code events are not shown here yet.</div><?
          echo $this->element('history', array('context_global' => true, 'events' => $history));
        } else {
          ?><div class='alert alert-info'><strong>No events!</strong> There is no activity related to your account.</div><?
        }?>
    </div>
</div>
