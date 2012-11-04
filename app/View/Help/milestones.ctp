<?php
/**
 *
 * View class for APP/help/milestones for the DevTrack system
 * Display the help page for logging time
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Help
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$this->Html->css('time.tempo', null, array ('inline' => false));
$this->Html->css('pages/help', null, array ('inline' => false));

echo $this->Bootstrap->page_header('Help! <small>How do I manage milestones?</small>'); ?>

<div class="row">
	<div class="span2">
		<?= $this->element('Sidebar/help') ?>
	</div>
	<div class="span10">
		<div class="well">
          <h3>Milestones</h3>
          <p>
            Milestones divide a project up into short, manageable chunks with a specific goal (they are often referred to as "timeboxes" or "sprints" in agile terminology).  To view the milestones for your project, click the '<a href="#"><i class="icon-road"></i>Milestones</a>' link in the project sidebar.  Now let's see what milestones are all about...
          </p>


          <h4>The anatomy of a milestone</h4>
          <p>
            Each milestone is made up of <a href='tasks'>tasks</a> which, when completed, mean that the milestone itself is considered complete.  The milestone itself contains very little extra information:
            <ol>
              <li><strong>Short name:</strong> A short name for display which is meaningful to your team - e.g. 'Sprint 1', or 'Implement user accounts and authentication'</li>
              <li><strong>Description:</strong> A more detailed description of the milestone, describing the overall goals</li>
              <li><strong>Completion target:</strong> When will the milestone be complete?</li>
            </ol>
          </p>

          <p>
            It is important to set a completion target and stick to this target - in order to do so, you may have to drop some features (remove tasks from the milestone) as you go.  More on this shortly.
          </p>

          <h4>Hang on, I've created a milestone and it shows up as closed! Bug alert!</h4>
          <p>
            Actually, milestones don't have their own open/closed status - it is based on the tasks <em>within</em> the milestone.  If any tasks are open, the milestone is open, otherwise it is closed.  If you've just created a milestone and it has no tasks, then there is no work to do on the milestone - so it shows up as closed! Simple.
          </p>

          <h4>Milestone overview</h4>
          <p>
            Once you've created at least one milestone, the project's milestone overview page will become useful - all milestones for the project are listed as lozenges, showing the short name and completion status (Exhibit 1).  There's also some quick edit/delete links (see the icons at the top right of each milestone).
          </p>
		</div>


          <div class="row-fluid span6 offset1">
            <div class="span9">
              <div class="well">
                <div class="row-fluid overview">

                  <div class="span5">
                    <h3><a href="#">User accounts</a></h3>
                  </div>

                  <div class="span7">
                    <a href="#" class="close delete"><i class="icon-remove-circle"></i></a>
                    <a href="#" class="close edit"><i class="icon-pencil"></i></a>
                    <p>
                      <small>
                        <span class="badge badge-info">1</span> closed
                        <span class="badge badge-success">1</span> resolved
                        <span class="badge badge-warning">1</span> in progress
                      </small>
                    </p>
                    <div class="progress progress-striped">
                      <div class="bar bar-info" style="width: 33.333333333333%;"></div>
                      <div class="bar bar-success" style="width: 33.333333333333%;"></div>
                      <div class="bar bar-warning" style="width: 33.333333333333%;"></div>
                      <div class="bar bar-danger" style="width: 0%;"></div>
                    </div>
                  </div>
               </div>
             </div>
          </div>
        </div>

        <div class="alert alert-info span7 offset1">
          Exhibit 1: Milestone lozenge
        </div>


      <div class="well span9">
        <h4>The Milestone board</h4>
        <p>
          Clicking on a milestone takes you to its milestone board display (also known as a "Kanban chart").  This is a visual overview of the status of all the milestone's tasks:
          <ol>
            <li><strong>Backlog:</strong> All the tasks that nobody has started working on yet</li>
            <li><strong>In Progress:</strong> Everything your team is working on at the moment</li>
            <li><strong>Completed:</strong> Everything that's done and dusted</li>
            <li><strong>Ice Box:</strong> All the project's tasks that are <em>not</em> attached to a milestone</li>
          </ol>
        </p>

        <p>
          As the milestone progresses, you may find that some tasks take longer than expected.  It is important to stay on track and finish the milestone on time, so you will need to drop some of the lower priority tasks - these will then end up in the icebox, ready to assign to another milestone.
        </p>

        <p>
          On the other hand, it may turn out you burn through the tasks a lot faster than expected! If this happens, you may want to pull some tasks out of the ice box by assigning them to the current milestone.  Hooray for productivity!
        </p>

      </div>
    </div>

</div>
