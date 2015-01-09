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
			$dropdown .= '<li><a class="label" title="'.__("Set priority: %s", h($priority['label'])).'" data-value="'.h($priority['name']).'" href="#">'.$priorityIcon.' '.$priority['label'].'</a></li>';
		}
		$dropdown .= '</ul>';
		return $dropdown;
	}


	// Renders a priority label/button that will activate the priority dropdown menu
	// set $task to null to just render a label instead with no button
	public function priorityDropdownButton($task, $textLabel = true) {

		if (is_numeric($task)) {
			$priorityId = $task;
			$task = null;
		} else {
			$priorityId = $task['Task']['task_priority_id'];
		}


		// Always add a nice tooltip on hover
		$tooltip = __("Priority: %s", h($this->_View->viewVars['task_priorities'][$priorityId]['label']));
		// If we're using a label, make it hidden on small displays
		$label = $textLabel? h($this->_View->viewVars['task_priorities'][$priorityId]['label']) : '';

		// Always show the icon
		$icon  = $this->Bootstrap->icon(
			$this->_View->viewVars['task_priorities'][$priorityId]['icon'],
			"white"
		);

		// Button class, if any
		$class = $this->_View->viewVars['task_priorities'][$priorityId]['class'];

		if (!empty($class)) {
			$class = "label-".h($class);
		}

		// If we've got no task ID, just render a label and no dropdown
		if ($task == null) {
			$button = '<span class="taskpriority label '.$class.'" title="'.$tooltip.'">'.$icon.' '.$label.'</span>';
		} else {
			$apiUrl = $this->Html->url(array('controller' => 'tasks', 'action' => 'edit', 'project' => $task['Project']['name'], 'api' => true));
			$button = '<button class="taskpriority label '.$class.' task-dropdown" title="'.$tooltip.'" data-api-url="'.$apiUrl.'" data-change="priority" data-id="'.h($priorityId).'" data-toggle="task_priority_dropdown">'.$icon.' '.$label.' <span class="caret"></span></button>';
		}
		return $button;
	}

	public function statusDropdownMenu() {
		$dropdown = '<ul class="dropdown-menu task-dropdown-menu" id="task_status_dropdown">';

		foreach ($this->_View->viewVars['task_statuses'] as $statusId => $status) {
			$dropdown .= '<li><a class="label label-'.$status['class'].'" data-value="'.h($status['name']).'" title="'.__("Set status: %s", $status['label']).'" href="#">'.$status['label'].'</a></li>';
		}
		$dropdown .= '</ul>';

		return $dropdown;

	}

	public function statusDropdownButton($task, $full = false) {

		if (is_numeric($task)) {
			$statusId = $task;
			$task = null;
		} else {
			$statusId = $task['Task']['task_status_id'];
		}

		$tooltip = __("Status: %s", h($this->_View->viewVars['task_statuses'][$statusId]['label']));
		$label = $this->_View->viewVars['task_statuses'][$statusId]['label'];
		$class = $this->_View->viewVars['task_statuses'][$statusId]['class'];

		if (!empty($class)) {
			$class = "label-".h($class);
		}

		// TODO display full length status name if the lozenge is large enough
		if (!$full) {
			$label = strtoupper(substr($label, 0, 1));
		}

		// If we've got no task ID, just render a label and no dropdown
		if ($task == null) {
			$button = '<span class="taskstatus label '.$class.'" title="'.$tooltip.'">'.h($label).'</span>';
		} else {
			$apiUrl = $this->Html->url(array('controller' => 'tasks', 'action' => 'edit', 'project' => $task['Project']['name'], 'api' => true));
			$button = '<button class="taskstatus label '.$class.' task-dropdown" title="'.$tooltip.'" data-api-url="'.$apiUrl.'" data-change="status" data-id="'.h($statusId).'" data-toggle="task_status_dropdown">'.h($label).' <span class="caret"></span></button>';
		}
		return $button;

	}

	public function typeDropdownMenu() {
		$dropdown = '<ul class="dropdown-menu task-dropdown-menu" id="task_type_dropdown">';

		foreach ($this->_View->viewVars['task_types'] as $typeId => $type) {
			$dropdown .= '<li><a class="label label-'.$type['class'].'" data-value="'.h($type['name']).'" title="'.__("Set type: %s", $type['label']).'" href="#">'.$type['label'].'</a></li>';
		}
		$dropdown .= '</ul>';

		return $dropdown;

	}

	public function typeDropdownButton($task) {
		if (is_numeric($task)) {
			$typeId = $task;
			$task = null;
		} else {
			$typeId = $task['Task']['task_type_id'];
		}

		$tooltip = __("Type: %s", h($this->_View->viewVars['task_types'][$typeId]['label']));
		$label = $this->_View->viewVars['task_types'][$typeId]['label'];
		$class = $this->_View->viewVars['task_types'][$typeId]['class'];

		if (!empty($class)) {
			$class = "label-".h($class);
		}

		// If we've got no task ID, just render a label and no dropdown
		if ($task == null) {
			$button = '<span class="tasktype label '.$class.'">'.h($label).'</span>';
		} else {
			$apiUrl = $this->Html->url(array('controller' => 'tasks', 'action' => 'edit', 'project' => $task['Project']['name'], 'api' => true));
			$button = '<button class="tasktype label '.$class.' task-dropdown" data-api-url="'.$apiUrl.'" data-change="type" data-id="'.h($typeId).'" data-toggle="task_type_dropdown">'.h($label).' <b class="caret"></b></button>';
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

	public function storyPointsControl($task, $full = false) {
		if (is_numeric($task)) {
			$points = $task ?: 0;
			$task = null;
		} else {
			$points = $task['Task']['story_points'] ?: 0;
		}

		$label = '';

		if ($task) {
			$label .= $this->Bootstrap->button("-", array('title' => __('Decrease story points'), 'class' => 'btn-inverse btn-storypoints btn-storypoints-control'));
		}

		$label .= $this->Bootstrap->button(__("<span class='points'>%d%s</span>", $points, $task?"":" SP"), array(
			'class' => 'disabled btn-inverse btn-storypoints',
			'title' => __n('%d story point', '%d story points', $points, $points)));
		
		if ($task) {
			$label .= $this->Bootstrap->button("+", array('title' => __('Increase story points'), 'class' => 'btn-inverse btn-storypoints btn-storypoints-control'));
		}

		if ($full) {
			$label .= __(" story points");
		}

		if ($task) {
			$label = "<span class=\"btn-group btn-group-storypoints\">$label</span>";
		}
		return $label;
	}

	// Assignee dropdown depends on the task (as we may have many tasks on the page from different projects).
	// This will be initially empty, then populated via ajax.
	public function assigneeDropdownMenu() {
		return '<ul class="dropdown-menu task-dropdown-menu" id="task_assignee_dropdown"></ul>';
	}

	public function assigneeDropdownButton($task, $size = 90) {

		if(isset($task['Assignee']['email'])){

			$tooltip = __("Assigned to: %s", h($task['Assignee']['name']));
			$label = $this->Gravatar->image($task['Assignee']['email'], array('size' => $size), array('alt' => $tooltip, 'title' => $tooltip));

		} else {

			$tooltip = __("Not assigned");
			$label = $this->Gravatar->image('', array('size' => $size, 'd' => 'mm'), array('alt' => $tooltip, 'title' => $tooltip));

		}

		$apiUrl = $this->Html->url(array('controller' => 'projects', 'action' => 'list_collaborators', 'api' => true, 'project' => $task['Project']['name']));

		$button = '<button class="label task-dropdown task-dropdown-assignee" title="'.h($tooltip).'" data-api-url="'.$apiUrl.'" data-change="assignee_id" data-toggle="task_assignee_dropdown" data-source="'.$apiUrl.'">'.$label.' <b class="caret"></b></button>';

		return $button;
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

	// Wrapper function to return all of the different dropdown menus
	// in one call, cos we probably want them all!
	public function allDropdownMenus() {
		return $this->typeDropdownMenu() .
			$this->statusDropdownMenu() .
			$this->priorityDropdownMenu() .
			$this->assigneeDropdownMenu();
	}
}
