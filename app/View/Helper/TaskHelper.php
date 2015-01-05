<?php
/**
 *
 * Helper class for Tasks section of the SourceKettle system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	SourceKettle Development Team 2012
 * @link		http://github.com/SourceKettle/sourcekettle
 * @package		SourceKettle.View.Helper
 * @since		SourceKettle v 0.1
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class TaskHelper extends AppHelper {


	public $helpers = array('Html', 'Bootstrap' => array('className' => 'TwitterBootstrap.TwitterBootstrap'), 'Gravatar');


	// Renders a dropdown menu box for setting task priorities
 	public function priorityDropdownMenu() {
		$dropdown = '<ul class="dropdown-menu task-dropdown-menu" id="task_priority_dropdown">';

		foreach ($this->_View->viewVars['task_priorities'] as $priorityId => $priority) {
			$priorityIcon = $this->Bootstrap->icon(
				$priority['icon'],
				"white"
			);
			$dropdown .= '<li><a class="label" title="'.__("Set priority: %s", h($priority['label'])).'" href="#">'.$priorityIcon.' '.$priority['label'].'</a></li>';
		}
		$dropdown .= '</ul>';
		return $dropdown;
	}


	// Renders a priority label/button that will activate the priority dropdown menu
	// set $taskId to null to just render a label instead with no button
	public function priorityDropdownButton($taskId, $priorityId, $textLabel = true) {

		// Always add a nice tooltip on hover
		$tooltip = __("Priority: %s", h($this->_View->viewVars['task_priorities'][$priorityId]['label']));
		// If we're using a label, make it hidden on small displays
		$label = $textLabel? '<span class="hidden-phone hidden-tablet">'.h($this->_View->viewVars['task_priorities'][$priorityId]['label']).'</span> ' : '';

		// Always show the icon
		$icon  = $this->Bootstrap->icon(
			$this->_View->viewVars['task_priorities'][$priorityId]['icon'],
			"white"
		);

		// Button class, if any
		$class = $this->_View->viewVars['task_priorities'][$priorityId]['class'];

		// If we've got no task ID, just render a label and no dropdown
		if ($taskId == null) {
			$button = '<span class="label label-'.h($class).'">'.$icon.' '.$label.'</span>';
		} else {
			$button = '<button class="label label-'.h($class).' task-dropdown" data-toggle="task_priority_dropdown">'.$icon.' '.$label.'</button>';
		}
		return $button;
	}

	public function statusDropdownMenu() {
		$dropdown = '<ul class="dropdown-menu task-dropdown-menu" id="task_status_dropdown">';

		foreach ($this->_View->viewVars['task_statuses'] as $statusId => $status) {
			$dropdown .= '<li><a class="label label-'.$status['class'].'" title="'.__("Set status: %s", $status['label']).'" href="#">'.$status['label'].'</a></li>';
		}
		$dropdown .= '</ul>';

		return $dropdown;

	}

	public function statusDropdownButton($taskId, $statusId) {
		$tooltip = __("Status: %s", h($this->_View->viewVars['task_statuses'][$statusId]['label']));
		$label = $this->_View->viewVars['task_statuses'][$statusId]['label'];
		$class = $this->_View->viewVars['task_statuses'][$statusId]['class'];

		// If we've got no task ID, just render a label and no dropdown
		if ($taskId == null) {
			$button = '<span class="label label-'.h($class).'">'.h($label).'</span>';
		} else {
			$button = '<button class="label label-'.h($class).' task-dropdown" data-toggle="task_status_dropdown">'.h($label).'</button>';
		}
		return $button;

	}

	public function typeDropdownMenu() {
		$dropdown = '<ul class="dropdown-menu task-dropdown-menu" id="task_type_dropdown">';

		foreach ($this->_View->viewVars['task_types'] as $typeId => $type) {
			$dropdown .= '<li><a class="label label-'.$type['class'].'" title="'.__("Set type: %s", $type['label']).'" href="#">'.$type['label'].'</a></li>';
		}
		$dropdown .= '</ul>';

		return $dropdown;

	}

	public function typeDropdownButton($taskId, $typeId) {
		$tooltip = __("Type: %s", h($this->_View->viewVars['task_types'][$typeId]['label']));
		$label = $this->_View->viewVars['task_types'][$typeId]['label'];
		$class = $this->_View->viewVars['task_types'][$typeId]['class'];

		// If we've got no task ID, just render a label and no dropdown
		if ($taskId == null) {
			$button = '<span class="label label-'.h($class).'">'.h($label).'</span>';
		} else {
			$button = '<button class="label label-'.h($class).' task-dropdown" data-toggle="task_type_dropdown">'.h($label).'</button>';
		}
		return $button;

	}
	public function milestoneLabel($task) {
		$label = "";
		if (isset($task['Milestone']['id'])){
			$label .= "<span class='label' title='".__('Milestone: %s', $task['Milestone']['subject'])."'>";
			$label .= $this->Html->link($this->Bootstrap->icon("road", "white"), array(
				'controller' => 'milestones',
				'action' => 'view',
				'project' => $task['Project']['name'],
				$task['Milestone']['id'],
			), array('escape' => false));
			$label .= "</span>";
		}
		return $label;
	}

	public function storyPointsLabel($task) {
		$points = $task['Task']['story_points'] ?: 0;
		$label = "<span class=\"btn-group btn-group-storypoints\">";
		$label .= $this->Bootstrap->button("-", array('class' => 'btn-inverse btn-storypoints'));
		$label .= $this->Bootstrap->button(__("<span class='points'>%d</span>", $points), array(
			'class' => 'disabled btn-inverse btn-storypoints',
			'title' => __n('%d story point', '%d story points', $points, $points)));
		$label .= $this->Bootstrap->button("+", array('class' => 'btn-inverse btn-storypoints'));
		$label .= "</span>";
		return $label;
	}

	public function assigneeLabel($task) {
		$label = "<div class=\"span2 task-lozenge-assignee\">";
		if(isset($task['Assignee']['email'])){
			$label .= $this->Gravatar->image($task['Assignee']['email'], array(), array('alt' => $task['Assignee']['name']));
		} else {
			$label .= $this->Gravatar->image('', array('d' => 'mm'), array('alt' => $task['Assignee']['name']));
		}
		$label .= "</div>";
		return $label;
	}

	public function treeRender($projectName, $tree) {
		echo "<ul>";
		
		echo "<li>";
		echo $this->Html->link("#".$tree['public_id'].": ".$tree['subject'], array(
			'controller' => 'tasks',
			'action' => 'view',
			'project' => $projectName,
			$tree['public_id']
		));
		if ($tree['loop']) {
			echo __("*** Circular dependency detected! ***");
		}
		echo "</li>";
	
		foreach ($tree['subTasks'] as $subTask) {
			$this->treeRender($projectName, $subTask);
		}
		echo "</ul>";
	}
}
