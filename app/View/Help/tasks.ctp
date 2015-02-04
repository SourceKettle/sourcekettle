<?php
/**
 *
 * View class for APP/help/tasks for the SourceKettle system
 * Display the help page for logging time
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Help
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$this->Html->css('time.tempo', null, array ('inline' => false));
$this->Html->css('pages/help', null, array ('inline' => false));
$this->Html->css('tasks', null, array ('inline' => false));
$this->Html->script('help', array('inline' => false));

// Fake example data
$project = array('Project' => array(
	'id' => '0',
	'name' => 'SourceKettle',
));

$tasks = array(
	array(
		'Task' => array(
			'id' => 0,
			'subject' => 'Do something',
			'task_status_id' => 1,
			'task_priority_id' => 2,
			'task_type_id' => 1,
			'public_id' => 0,
		),
		'TaskType' => array(
			'name' => 'bug',
		),
		'Project' => array(
			'id' => '0',
			'name' => 'SourceKettle',
		),
		'Assignee' => array(
			'id' => 0,
			'name' => 'Andy Newton',
			'email' => 'andy@example.org',
		),
	),

	array(
		'Task' => array(
			'id' => 0,
			'subject' => 'Do something else',
			'task_status_id' => 2,
			'task_priority_id' => 3,
			'task_type_id' => 2,
			'public_id' => 0,
		),
		'TaskType' => array(
			'name' => 'enhancement',
		),
		'Project' => array(
			'id' => '0',
			'name' => 'SourceKettle',
		),
		'Assignee' => array(
			'id' => 0,
			'name' => 'Phill Whittlesea',
			'email' => 'phill@example.org',
		),
	),

	array(
		'Task' => array(
			'id' => 0,
			'subject' => 'Do something',
			'task_status_id' => 4,
			'task_priority_id' => 4,
			'task_type_id' => 1,
			'dependenciesComplete' => true,
			'public_id' => 0,
		),
		'DependsOn' => array(0 => array('fish')),
		'TaskType' => array(
			'name' => 'bug',
		),
		'Project' => array(
			'id' => '0',
			'name' => 'SourceKettle',
		),
		'Assignee' => array(
			'name' => 'unassigned',
		),
	),
);

?>

<div class="row-fluid">
	<div class="span2">
		<?= $this->element('Sidebar/help') ?>
	</div>
	<div class="span10">
      <div class="well">
		<h3>Tasks</h3>
		<p>
          The basic unit of work for a project is the task - a chunk of work to be carried out by one of the project team.  To view tasks for a project, click the '<a href="#"><i class="icon-file"></i> Tasks</a>' link in the project sidebar.  Let's take a look at what constitutes a task.
        </p>

        <h4>The anatomy of a task</h4>
        <p>
          A task consists of 4 pieces of required information:
          <ol>
            <li><strong>The subject:</strong> A brief but informative description of what the task entails</li>
            <li><strong>A task priority:</strong> How critical it is to get this task finished</li>
            <li><strong>A type:</strong> What type of task is this - bug, enhancement, question etc.</li>
            <li><strong>A status:</strong> Open, In Progress, Resolved or Closed - basically, is it still 'to do', and is anybody working on it?</li>
          </ol>

          And 4 pieces of optional information:
          <ol start='5'>
            <li><strong>Assignee:</strong> Who is assigned to work on this task?</li>
            <li><strong>Dependencies:</strong> Any other tasks within the project that must be completed before this task can start</li>
            <li><strong>Milestone:</strong> The associated <a href='milestones'><i class="icon-road"></i>project milestone</a></li>
            <li><strong>Description:</strong> A longer, more detailed explanation of what needs doing</li>
          </ol>

          Although these are optional, we would recommend setting from the outset:
          <ul>
            <li>The description - it will make it easier for the asignee to understand what to do, unless it's a trivial task that can be described in 50 characters!</li>
            <li>The milestone - generally speaking tasks not assigned to a milestone will fall by the wayside...</li>
          </ul>

		  Tasks also have two optional estimates (educated guesses) which you may wish to use:
		  <ul>
		    <li><strong>Time estimate:</strong> An estimate at how long the task will take</li>
		    <li><strong>Story points:</strong> An estimate of the relative complexity of the task, generally used when planning; see <a href='http://agilefaq.wordpress.com/2007/11/13/what-is-a-story-point/'>this page</a> for a description of how to use story points</li>
		  </ul>

          The starting status of all tasks is "Open" - i.e. "We know this needs doing, but we haven't started yet".
        </p>

        <h4>Task comments</h4>
        <p>
          Comments are a way of logging extra useful information about a task, as well as discussing it with other project members.  You can add comments to ask questions, note down useful information for the asignee, explain why you've decided the login page should be pink - anything you like!
        </p>

      </div>

      <div class="well">
        <h4>The task display board</h4>
        <p>
          If your project doesn't have any tasks yet, click the <button class="btn btn-mini btn-primary">Create Task</button> button to get started.
        </p>
        <p>
          Once you've created a task (or a few tasks - treat yourself!), they will show up in the task display board.  This is a master list of all tasks for the project,
          which you can filter by:
          <ul>
            <li>Assignment (who will be actually doing the work?)</li>
			<li>Status (what is currently happening?)</li>
			<li>Priority (how urgent is it?)</li>
			<li>Milestone (which milestone, if any, is the task part of?)</li>
          </ul>
        </p>

        <p>
          The panel displays a list of tasks (matching your filter selections) in order of priority.  Click on a task to display the full detail page for that task.
        </p>
      </div>


    <div class="span10 example">
        <div class="row-fluid">
            <?= $this->element('Task/topbar_filter', array(
				'collaborators' => array(),
				'selected_statuses' => array(),
				'selected_priorities' => array(),
				'selected_types' => array(),
				'selected_milestones' => array(),
				'milestones' => array('open' => array(), 'closed' => array()),
			)) ?>
        </div>
		<div class="row-fluid well col">
            <h2><?=__("Task list")?></h2>
            <hr />
			<ul class="sprintboard-droplist">
			  <? foreach ($tasks as $task) {
			  echo $this->element('Task/lozenge', array('task' => $task));
			  } ?>
			</ul>
		</div>
    </div>

      <div class="alert alert-info span7 offset1">
        <strong>Exhibit 2:</strong> Task filtering controls
      </div>


     <div class="well span9">
        

        <h4>Task lozenges</h4>
        <p>
          Wherever tasks are listed, such as the display board or milestone board, they are displayed as lozenges containing a brief overview of the task.  At a glance, you can see:
          <ul>
            <li><strong>Task type:</strong> Indicated by the coloured strip on the left edge of the lozenge</li>
            <li><strong>Task ID and subject:</strong> Displayed as text</li>
            <li><strong>Priority:</strong> A black-and-white indicator with icon</li>
            <li><strong>Status:</strong> A coloured indicator showing the current status</li>
            <li><strong>Milestone:</strong> A milestone icon (<span class="label"><i class="icon-road"></i></span>) appears if the task is attached to one, click the icon to view the milestone</li>
            <li><strong>Dependencies:</strong> A red 'D' indicates the task has incomplete dependencies, a green 'D' indicates all dependencies complete; no 'D' indicates no dependencies.</li>
            <li><strong>Assignee:</strong> Displayed as the user's gravatar image</li>
          </ul>
        </p>

      </div>


     <div class="well span9">

        <h4>Task detail view</h4>
        <p>
          Clicking on a task brings up a more detailed overview.  From this view you can:
          <ul>
            <li><strong>Edit the task:</strong> Change its details</li>
            <li><strong>(Re-)assign the task:</strong> Set the asignee</li>
            <li><strong>Change the status:</strong> This depends on the current status and whether you are assigned - the topbar will contain an appropriate button to e.g. close or re-open the task</li>
          </ul>
        </p>

        <p>
          If the task is open and assigned to you, you can log time to the task from here.
        </p>

      </div>


	</div>
</div>
