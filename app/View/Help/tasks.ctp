<?php
/**
 *
 * View class for APP/help/tasks for the DevTrack system
 * Display the help page for logging time
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/SourceKettle/devtrack
 * @package       DevTrack.View.Help
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$this->Html->css('time.tempo', null, array ('inline' => false));
$this->Html->css('pages/help', null, array ('inline' => false));
$this->Html->css('tasks.index', null, array ('inline' => false));

echo $this->Bootstrap->page_header('Help! <small>How do I manage tasks?</small>'); ?>

<div class="row">
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
            <li>Assignment (assigned to you, somebody else, nobody, all) - select from the top bar (<strong>exhibit 1</strong>)</li>
            <li>Status, type and milestone - select from the right hand panel (<strong>exhibit 2</strong>)</li>
          </ul>
        </p>

        <p>
          The centre panel displays a list of tasks (matching your filter selections) in order of priority.  Click on a task to display the full detail page for that task.
        </p>
      </div>

      <div class="well col span7 offset1">
        <div class="btn-group"><a href="/project/bluedog/tasks/." class="btn"><strong>Assigned to you</strong></a><a href="/project/bluedog/tasks/others" class="btn">Assigned to others</a><a href="/project/bluedog/tasks/nobody" class="btn">Assigned to nobody</a><a href="/project/bluedog/tasks/all" class="btn">All tasks</a></div>
      </div>

      <div class="alert alert-info span7 offset1">
        <strong>Exhibit 1:</strong> Assignment selections in top bar
      </div>

      <div class="well span7 offset1"> 

          <div class="row-fluid">

            <div class="span6">
                <ul id="taskStatus" class="nav nav-pills nav-stacked">
                    <li class="active">
                      <a href="#" status-id="0">All</a>
                    </li>
                    <li class="">
                        <a href="#" status-id="1">Open</a>
                    </li>
                    <li class="">
                        <a href="#" status-id="2">In Progress</a>
                    </li>
                    <li class="">
                        <a href="#" status-id="3">Resolved</a>
                    </li>
                    <li class="">
                        <a href="#" status-id="4">Closed</a>
                    </li>
                </ul>
            </div>

            <div id="priorityCheckboxes" class="span6">
                <div class="tasktype label label-important">
                  <input type="hidden" name="data[bug]" id="bug_" value="0"/>
                  <input type="checkbox" name="data[bug]"  value="1" checked="checked" id="bug"/>
                  bug
                </div>
                <div class="tasktype label label-warning">
                  <input type="hidden" name="data[duplicate]" id="duplicate_" value="0"/>
                  <input type="checkbox" name="data[duplicate]"  value="2" checked="checked" id="duplicate"/>
                  duplicate
                </div>
                <div class="tasktype label label-success">
                  <input type="hidden" name="data[enhancement]" id="enhancement_" value="0"/>
                  <input type="checkbox" name="data[enhancement]"  value="3" checked="checked" id="enhancement"/>
                  enhancement
                </div>
                <div class="tasktype label">
                  <input type="hidden" name="data[invalid]" id="invalid_" value="0"/>
                  <input type="checkbox" name="data[invalid]"  value="4" checked="checked" id="invalid"/>
                  invalid
                </div>
                <div class="tasktype label label-info">
                  <input type="hidden" name="data[question]" id="question_" value="0"/>
                  <input type="checkbox" name="data[question]"  value="5" checked="checked" id="question"/>
                  question
                </div>
                <div class="tasktype label label-inverse">
                  <input type="hidden" name="data[wontfix]" id="wontfix_" value="0"/>
                  <input type="checkbox" name="data[wontfix]"  value="6" checked="checked" id="wontfix"/>
                  wontfix
                </div>
                <div class="tasktype label label-info">
                  <input type="hidden" name="data[documentation]" id="documentation_" value="0"/>
                  <input type="checkbox" name="data[documentation]"  value="7" checked="checked" id="documentation"/>
                  documentation
                </div>
                <div class="tasktype label label-info">
                  <input type="hidden" name="data[meeting]" id="meeting_" value="0"/>
                  <input type="checkbox" name="data[meeting]"  value="8" checked="checked" id="meeting"/>
				  meeting
                </div>
            </div>

        </div>

        <hr>

        <p class="lead span12">
          <strong>Milestone:</strong>
          <select name="data[milestone]" id="milestone">
            <option value=""></option>
            <option value="1">Initial planning</option>
            <option value="2">Coding frenzy</option>
          </select>
        </p>
        
      </div>

      <div class="alert alert-info span7 offset1">
        <strong>Exhibit 2:</strong> Task filtering controls
      </div>


     <div class="well span9">
        

        <h4>Task lozenges</h4>
        <p>
          Wherever tasks are listed, such as the display board or milestone board, they are displayed as lozenges containing a brief overview of the task (exhibit 3).  At a glance, you can see:
          <ul>
            <li><strong>Task type:</strong> Indicated by the coloured strip on the left edge of the lozenge - matches up with the type colours in exhibit 2</li>
            <li><strong>Task ID and subject:</strong> Displayed as text</li>
            <li><strong>Priority:</strong> A black-and-white indicator with icon</li>
            <li><strong>Status:</strong> A coloured indicator showing the current status</li>
            <li><strong>Dependencies:</strong> A red 'D' indicates the task has incomplete dependencies, a green 'D' indicates all dependencies complete; no 'D' indicates no dependencies.</li>
            <li><strong>Assignee:</strong> Displayed as the user's gravatar image</li>
          </ul>
        </p>

      </div>

      <div class="well col span7 offset1">
        <div class="task">
          <div class="well type_bar_enhancement">
            <div class="row-fluid">
                <div>
                    <div class="span10">
                        <p>
                            <a href="#"><strong>#6</strong> - Create help page for tasks</a>
                        </p>
                        <span class="label label-inverse">Urgent <i class="icon-exclamation-sign icon-white"></i></span>
                        <span class="label label-warning">In Progress</span>
                        <span class="label label-important" title="Dependencies incomplete">D</span>
                    </div>
                    <div class="span2">
                        <img src="https://secure.gravatar.com/avatar/6258d5a7f3119188649d2562a3836641.jpg?d=mm" alt="Andy Newton" height="80" width="80">
                    </div>
                </div>
            </div>
          </div>
        </div>
      </div>

      <div class="alert alert-info span7 offset1">
        <strong>Exhibit 3:</strong> An example task lozenge
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
