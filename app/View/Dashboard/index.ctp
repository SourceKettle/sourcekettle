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
  $this->Html->css('tasks', null, array ('inline' => false));
  $this->Html->script('tasks', array ('inline' => false));
?>

<?= $this->Task->allDropdownMenus() ?>

<div class="row-fluid">
    <div class="span6">
      <h3>Assigned tasks</h3>
	  <? if (!$sourcekettle_config['Features']['task_enabled']['value']) {?>
		<div class='alert'><?=__('Task tracking is disabled on this system.')?></div>
      <? } elseif (!empty($tasks)){
	  	echo "<p>";
		echo $this->Html->link(__("View kanban chart"), array('controller' => 'tasks', 'action' => 'personal_kanban'));
	  	echo "</p>";
	    echo "<ul class='sprintboard-droplist'>";
        foreach ($tasks as $task){
          echo $this->element('Task/lozenge', array('task' => $task, 'span' => 12));
        }
		echo "</ul>";
      } else {
        ?><div class='alert alert-success'><?=__("You have no assigned tasks! Why don't you assign yourself one?")?></div><?
      }?>
    </div>
    <div class="span6">
        <h3><?=__("Most recent projects")?></h3>
        <div class='row'>
          <?
			 $options = array(
			 	'links' => array(
					array(
						'text' => __("My projects"),
						'url' => array(
							'action' => '.',
							'controller' => 'projects',
						),
					),
					array(
						'text' => __("Team projects"),
						'url' => array(
							'action' => 'team_projects',
							'controller' => 'projects',
						),
					),
					array(
						'text' => __("New Project"),
						'url' => array(
							'action' => 'add',
							'controller' => 'projects',
						),
						'active' => true,
						'pull-right' => true,
					),
				),
            );
            echo $this->element('Topbar/pills', array('options' => $options, 'span' => 12));

          ?>
        </div>

        <? if (!empty($projects)){
    		foreach ($projects as $project){
				echo $this->Element('Project/block', array('project' => $project, 'nospan' => true));
			}
        } else {
          echo $this->element('Project/noprojectsalert');
        }?>
    </div>
</div>

<div class='row'>
    <div class='span10'>
      <h3><?=__("Recent events")?></h3>
        <? if (!empty($history)){
          ?><div class='alert alert-info'><strong>In progress!</strong> Source code events are not shown here yet.</div><?
          echo $this->element('history', array('context_global' => true, 'events' => $history));
        } else {
          ?><div class='alert alert-info'><strong><?=__("No events!")?></strong> <?=__("There is no activity related to your account.")?></div><?
        }?>
    </div>
</div>
