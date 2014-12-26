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


/**
 * helpers
 *
 * @var string
 * @access public
 */
	public $helpers = array('Html', 'Bootstrap' => array('className' => 'TwitterBootstrap.TwitterBootstrap'), 'Gravatar');

/**
 * priority function.
 *
 * @access public
 * @param mixed $id
 * @return void
 */
	public function priority($id, $textLabel = true) {
		$tooltip = __("Priority: %s", h($this->_View->viewVars['task_priorities'][$id]['label']));
		$label = $textLabel? '<span class="hidden-phone hidden-tablet">'.h($this->_View->viewVars['task_priorities'][$id]['label']).'</span> ' : '';
		$icon  = $this->_View->viewVars['task_priorities'][$id]['icon'];
		$class = $this->_View->viewVars['task_priorities'][$id]['class'];
		return $this->Bootstrap->label($label . $this->Bootstrap->icon($icon, "white"), "inverse", array('class' => "taskpriority $class", "title" => $tooltip));
	}

/**
 * status function.
 *
 * @access public
 * @param mixed $id
 * @return void
 */
	public function status($id) {
		return $this->_View->viewVars['task_statuses'][$id]['label'];
	}

/**
 * Get a label to show the status of a task
 * @access public
 * @param mixed $id
 * @return void
 */
	public function statusLabel($id) {
		$tooltip = __("Status: %s", h($this->_View->viewVars['task_statuses'][$id]['label']));
		$label = $this->_View->viewVars['task_statuses'][$id]['label'];
		$class = $this->_View->viewVars['task_statuses'][$id]['class'];
		return $this->Bootstrap->label($label, $class, array ("class" => "taskstatus", "title" => $tooltip));

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
		$label = "<span class=\"btn-group btn-group-storypoints\">";
		$label .= $this->Bootstrap->button("-", array('class' => 'btn-inverse btn-storypoints'));
		$label .= $this->Bootstrap->button(__("<span class='points'>%d</span> SP", $task['Task']['story_points'] ?: 0), array('class' => 'disabled btn-inverse btn-storypoints', 'title' => __('Story points')));
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

/**
 * type function.
 *
 * @access public
 * @param mixed $id
 * @return void
 */
	public function type($id) {
		$label = $this->_View->viewVars['task_types'][$id]['label'];
		$class = $this->_View->viewVars['task_types'][$id]['class'];
		return $this->Bootstrap->label($label, $class);
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
