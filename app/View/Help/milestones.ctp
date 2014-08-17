<?php
/**
 *
 * View class for APP/help/milestones for the SourceKettle system
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
$this->Html->css('tasks.index', null, array ('inline' => false));
$this->Html->css("milestones.index", null, array ('inline' => false));
$this->Html->script("jquery-ui.min", array ('inline' => false));
$this->Html->script("jquery.ui.touch-punch.min", array ('inline' => false));
$this->Html->script('help.milestones', array('inline' => false));
$this->Html->script('help', array('inline' => false));

// Some fake example data
$milestone = array(
	'Milestone' => array(
		'id' => 0,
		'is_open' => true,
		'subject' => 'User accounts',
		'description' => 'Create a user account system, possibly with bees',
		'oTasks' => 5,
		'iTasks' => 2,
		'rTasks' => 3,
		'cTasks' => 3,
		'dPoints' => 4,
		'tPoints' => 9,
	),
);
$project = array('Project' => array(
	'id' => '0',
	'name' => 'SourceKettle',
));

echo $this->Bootstrap->page_header('Help! <small>How do I manage milestones?</small>'); ?>

<div class="row">
	<div class="span2">
		<?= $this->element('Sidebar/help') ?>
	</div>

	<div class="span10">


		<!-- Section 1 -->
		<div class="row-fluid">
		<div class="well span12">
          <h3>Milestones</h3>
          <p>
            Milestones divide a project up into short, manageable chunks with a specific goal (they are often referred to as "timeboxes" or "sprints" in agile terminology).  To view the milestones for your project, click the '<a href="#"><i class="icon-road"></i>Milestones</a>' link in the project sidebar.  Now let's see what milestones are all about...
          </p>


          <h4>The anatomy of a milestone</h4>
          <p>
            Each milestone is made up of <a href='tasks'>tasks</a> which, when completed, mean that the milestone itself should be considered complete.  The milestone itself contains very little extra information:
            <ol>
              <li><strong>Short name:</strong> A short name for display which is meaningful to your team - e.g. 'Sprint 1', or 'Implement user accounts and authentication'</li>
              <li><strong>Description:</strong> A more detailed description of the milestone, describing the overall goals</li>
              <li><strong>Completion target:</strong> When will the milestone be complete?</li>
			  <li><strong>Open:</strong> Is the milestone currently open? (i.e. you can assign tasks to it and people are working on the tasks)</li>
            </ol>
          </p>

          <p>
            It is important to set a completion target and stick to this target - in order to do so, you may have to drop some features (remove tasks from the milestone) as you go.  More on this shortly.
          </p>

          <h4>Milestone overview</h4>
          <p>
            Once you've created at least one milestone, the project's milestone overview page will become useful - all milestones for the project are listed as lozenges, showing the short name and completion status (Exhibit 1).  There's also some quick controls to edit/delete/close/re-open the milestone (see the icons at the top right of each milestone).
          </p>
		</div>
		</div>
		<!-- End section 1 -->



		<!-- Exhibit 1 -->
		<div class="row-fluid example">
			<?=$this->element('Milestone/block', array('milestone' => $milestone, 'project' => $project))?>
		</div>

		<div class="row-fluid">
		<div class="span10 offset1">

    		<div class="alert alert-info span12">
		       Exhibit 1: Milestone lozenge
		    </div>

		</div>
		</div>
		<!-- End exhibit 1 -->



		<!-- Section 2 -->
		<div class="row-fluid">
      	<div class="well span12">

        <h4>The Milestone board</h4>
        <p>
          Clicking on a milestone takes you to its milestone board display (also known as a "Kanban chart" - Exhibit 2).  This is a visual overview of the status of all the milestone's tasks:
          <ol>
            <li><strong>Backlog:</strong> All the tasks that nobody has started working on yet</li>
            <li><strong>In Progress:</strong> Everything your team is working on at the moment</li>
            <li><strong>Completed:</strong> Everything that's done and dusted</li>
            <li><strong>Ice Box:</strong> Planned tasks for the milestone that will <em>not</em> be completed due to time constraints</li>
          </ol>
        </p>

		</div>
		</div>
		<!-- End section 2 -->


		<!-- Start exhibit 2 -->
		<div class="row-fluid">
		<div class="span10 offset1">

			<!-- Backlog column with one task -->
			<ul class="well col sprintboard-droplist span4 sprintboard-column ui-sortable">
				<h4>Backlog</h4>
				<hr>

				<li class='draggable'><div class="task-container">
					<div class="task"><div class="well type_bar_enhancement">

						<div class="row-fluid">
						<div class="span10">
							<p><a href="#" title="Click to go to the task"><strong>#2</strong> - A task</a></p>
							<span class="label label-inverse" title="Severity">Major <i class="icon-upload icon-white"></i></span> <span class="label label-important taskstatus" title="Status (changes when dragged between columns)">Open</span>
						</div>

						<div class="span2">
							<img src="https://secure.gravatar.com/avatar/placeholder.jpg?d=mm" alt="Not assigned" title="Nobody is assigned to the task" height="80" width="80"/>
						</div>
						</div>

					</div></div>
				</div></li>
			</ul>

			<!-- In progress column with one task -->
			<ul class="well col sprintboard-droplist span4 sprintboard-column ui-sortable">
				<h4>In progress</h4>
				<hr>
				<li class='draggable'><div class="task-container">
					<div class="task"><div class="well type_bar_bug">

						<div class="row-fluid">
						<div class="span10">
							<p><a href="#" title="Click to go to the task"><strong>#3</strong> - Another task</a></p>
							<span class="label label-inverse" title="Severity">Urgent <i class="icon-exclamation-sign icon-white"></i></span> <span class="label label-warning taskstatus" title="Status (changes when dragged between columns)">In progress</span>
						</div>

						<div class="span2">
							<img src="https://secure.gravatar.com/avatar/placeholder.jpg" alt="Diana Developer" title="Gravatar of the assigned developer" height="80" width="80"/>
						</div>
						</div>

					</div></div>
				</div></li>
			</ul>

			<!-- Completed column with one task -->
			<ul class="well col sprintboard-droplist span4 sprintboard-column ui-sortable">
				<h4>Completed</h4>
				<hr>
				<li class='draggable'><div class="task-container">
					<div class="task"><div class="well type_bar_bug">

						<div class="row-fluid">
						<div class="span10">
							<p><a href="#" title="Click to go to the task"><strong>#3</strong> - Finished task</a></p>
							<span class="label label-inverse" title="Severity">Blocker <i class="icon-ban-circle icon-white"></i></span> <span class="label label-success taskstatus" title="Status (changes when dragged between columns)">Resolved</span>
						</div>

						<div class="span2">
							<img src="https://secure.gravatar.com/avatar/placeholder.jpg" alt="Diana Developer" title="Gravatar of the assigned developer" height="80" width="80"/>
						</div>
						</div>

					</div></div>
				</div></li>
			</ul>
		</div>
		</div>

		<!-- Icebox with one task -->
		<div class="row-fluid">
		<div class="span10 offset1">
			<ul class="well col sprintboard-droplist sprintboard-icebox span12 sprintboard-column ui-sortable">
				<h4>Icebox</h4>
				<hr>
				<li class='draggable'><div class="task-container">
					<div class="task"><div class="well type_bar_bug">

						<div class="row-fluid">
						<div class="span10">
							<p><a href="#" title="Click to go to the task"><strong>#3</strong> - Won't do this</a></p>
							<span class="label label-inverse" title="Severity">Minor <i class="icon-download icon-white"></i></span> <span class="label taskstatus" title="Status (changes when dragged between columns)">Dropped</span>
						</div>

						<div class="span2">
							<img src="https://secure.gravatar.com/avatar/placeholder.jpg?d=mm" alt="Not assigned" title="Nobody is assigned to the task" height="80" width="80"/>
						</div>
						</div>

					</div></div>
				</div></li>
			</ul>

		</div>
		</div>

		<div class="row-fluid">
		<div class="span10 offset1">

    		<div class="alert alert-info span12">
		       Exhibit 2: Milestone board
		    </div>

		</div>
		</div>
		<!-- End exhibit 2 -->

		<!-- Section 3 -->
		<div class="row-fluid">
		<div class="well span12">

        <p>
          As the milestone progresses, you may find that some tasks take longer than expected.  It is important to stay on track and finish the milestone on time, so you will need to drop some of the lower priority tasks - these will then end up in the icebox.
        </p>

        <p>
          On the other hand, it may turn out you burn through the tasks a lot faster than expected! If this happens, you may want to pull some tasks out of the ice box.  Hooray for productivity!
        </p>

		<p>
		  When the milestone is finished, click the <a class="btn btn-small" href="#">Close</a> button. Any tasks for the milestone that are marked as 'resolved' will be automatically marked as 'closed'. <strong>Note:</strong> You should normally only close the milestone once all tasks are finished. If any tasks are <em>not</em> finished, you will be asked what to do: move them to another milestone, or just unlink them from the current milestone.
		</p>


		</div>
		</div>
		<!-- End section 3 -->

	</div>
</div>
