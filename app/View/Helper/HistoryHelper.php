<?php
/**
 * Helper for rendering history log entries
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	 SourceKettle Development Team 2014
 * @link			http://github.com/SourceKettle/sourcekettle
 * @package		SourceKettle.View.Helper
 * @since		 SourceKettle v 1.5
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class HistoryHelper extends AppHelper {

/**
 * Renders a log entry based on its type, e.g. task changes
 * will be shown differently to project changes.
 */
	public $helpers = array('Html', 'Gravatar', 'Bootstrap');

	public function render ( $event, $showDate = false ) {

		/*
		 * Stores the display preferences for the activity blocks
		 */
		$prefs = array(
			'Collaborator' => array('icon' => 'user', 'color' => 'warning'),
			'Time'		   => array('icon' => 'time', 'color' => 'info'),
			'Source'	   => array('icon' => 'pencil', 'color' => 'success'),
			'Task'		   => array('icon' => 'file', 'color' => 'important'),
			'Milestone'	   => array('icon' => 'road', 'color' => ''),
		);
		
		// Optionally print the date. Usually we won't do this, we'll
		// print the date once then show all entries for that date.
		if ( $showDate ) {
			$date = strtotime($event['modified']);
			echo '<strong>' . date('Y-m-d H:i:s', $date) . '</strong>';
		}
	
		// Create Actioner String/Link if exists
		if ( $event['Actioner']['exists'] ) {
			$actioner = $this->Html->link(
			  $event['Actioner']['name'], array(
				'controller' => 'users',
				'action'	 => 'view',
				'api' => false,
				$event['Actioner']['id']
			  )
			);
		} else {
			$actioner = $event['Actioner']['name'];
		}
	
		// Create Subject String/Link if exists
		if ( $event['Subject']['exists'] ) {
			$subject = $this->Html->link(
				$event['Subject']['title'],
				(isset($event['url'])) ? $event['url'] : array(
					'project' => $event['Project']['name'],
					'controller' => Inflector::pluralize(strtolower($event['Type'])),
					'action' => 'view',
					'api' => false,
					$event['Subject']['id']
				)
			);
		} else {
			$subject = $event['Subject']['title'];
		}
	
		// Create Project Link
		$project = $this->Html->link(
			$event['Project']['name'],
			array(
				'controller' => 'projects',
				'action' => 'view',
				'project' => $event['Project']['name']
			)
		);

		// Field name and new/old values
		$field = h($event['Change']['field']);
		$old = h($event['Change']['field_old']);
		$new = h($event['Change']['field_new']);
	
		// Work out what type of change it was
		switch ( $event['Change']['field'] ) {
			case '+':
				$change_type = 'create';
				break;
			case '-':
				$change_type = 'delete';
				break;
			default;
				$change_type = 'update';
		}

		// Now work out how to phrase it in the log...
		switch ( strtolower($event['Type']).'.'.$change_type ) {
			case 'collaborator.update':
				$log_string = __(
					"%s updated %s's role &rarr; '%s' changed from '%s' to '%s'",
					$actioner, $subject, $field, $old, $new
				);
			break;

			case 'collaborator.create':
				$log_string  = __(
					"%s was added to the project by %s",
					$subject, $actioner
				);
			break;

			case 'collaborator.delete':
				$log_string  = __(
					"%s removed %s from the project",
					$actioner, $subject
				);
			break;

			case 'time.update':
				$log_string  = __(
					"%s updated some %s",
					$actioner, $subject
				);
			break;

			case 'time.create':
				$log_string  = __(
					"%s logged %s to the project",
					$actioner, $subject
				);
			break;

			case 'time.delete':
				$log_string  = __(
					"%s removed %s from the project",
					$actioner, $subject
				);
			break;

			case 'source.create':
				$log_string  = __(
					"%s commited code to the project &rarr; %s",
					$actioner, $subject
				);
			break;

			case 'task.update':
				$log_string  = __(
					"%s updated task %s &rarr; '%s' was modified",
					$actioner, $subject, $field
				);
			break;

			case 'task.create':
				$log_string  = __(
					"%s added a task (%s) to the project",
					$actioner, $subject
				);
			break;

			case 'task.delete':
				$log_string  = __(
					"%s deleted task %s from the project",
					$actioner, $subject
				);
			break;

			case 'milestone.update':
				$log_string  = __(
					"%s updated milestone %s &rarr; '%s' was modified",
					$actioner, $subject, $field
				);
			break;

			case 'milestone.create':
				$log_string  = __(
					"%s added a milestone (%s) to the project",
					$actioner, $subject
				);
			break;

			case 'milestone.delete':
				$log_string  = __(
					"%s deleted milestone %s from the project",
					$actioner, $subject
				);
			break;

			default:
				$log_string = __(
					"%s performed a %s on the %s %s",
					$actioner, $change_type, $event['Type'], $subject
				);
			break;
		}

		echo '<p>';
	
		// Who made the change?
		echo $this->Gravatar->image($event['Actioner']['email'], array('size' => 30), array('alt' => $event['Actioner']['name']));
	
		echo ' ';
	
		// What type was it? Show an icon
		echo $this->Bootstrap->label(
			$this->Bootstrap->icon($prefs[$event['Type']]['icon'], "white"),
			$prefs[$event['Type']]['color']
		);
	
		// Show the details
		echo " $log_string - ";
	
		// If we're not prefixing everything with a date, put the time on the end.
		if (!$showDate) {
			echo '<small>'.date('H:i', strtotime($event['modified'])).'</small>';
		}
		echo '</p>';


	}
}
