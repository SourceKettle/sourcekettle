<?php
/**
 *
 * View class for APP/help/time for the DevTrack system
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

echo $this->Bootstrap->page_header('Help! <small>How do I log time?</small>'); ?>

<div class="row">
	<div class="span2">
		<?= $this->element('Sidebar/help') ?>
	</div>
	<div class="span10">
		<div class="well">
			<h3>Logging time</h3>
			<p>On this help page, how to log, edit and view time is discussed. But first, what makes up a piece of logged time?</p>

			<h4>The anatomy of logged time</h4>
			<p>Logged time consists of 2 pieces of required information:</p>
			<ol>
				<li><strong>Time Taken:</strong> signifies how long you spent doing whatever it was that you were doing.</li>
				<li><strong>Date:</strong> specifies when exactly you did the work you did.</li>
			</ol>
			<p>And 2 pieces of optional information:</p>
			<ol start="3">
				<li><strong>Description:</strong> most of the time, telling your team what you were up to is a good idea.</li>
				<li><strong>Attached Task:</strong> allows you to log time to a specific task.</li>
			</ol>
			<p>Although optional, we would recommend that you should always provide all four pieces of information. In a couple of months time, it will make things easier for you and your team!</p>

			<h4>The 'User Breakdown' page</h4>
			<p>Upon first selecting '<a href="#"><i class="icon-book"></i> Time</a>' from the project sidebar, you will be presented with what we call a 'user breakdown' page. Depending upon the state of your project you may see different things.</p>
			<p>If you (or a team member) has previously logged time to the project you will be presented with a fancy table and pie chart showing the breakdown of users efforts (<strong>exhibit 1</strong>).</p>
			<p>If you have yet to log time to the project, just click the <button class="btn btn-mini btn-primary">Log time</button> button!</p>
		</div>

		<div class="row-fluid">
			<div class="offset4 span4">
				<div class="well example" style="text-align:center">
					<h4>Time Contribution</h4>
					<img src="http://0.chart.apis.google.com/chart?chbh=a&amp;chds=a&amp;chs=450x165&amp;chf=bg%2Cs%2C00000000&amp;cht=p3&amp;chma=0%2C0%2C0%2C0&amp;chl=User+1%7CUser+2&amp;chd=t:911,90&amp;chco=3266cc&amp;" alt="">
					<h5><small>(16 hours 30 mins total)</small></h5>
				</div>
				<div class="alert alert-info">
					<strong>Exhibit 1:</strong> Time pie chart.
				</div>
			</div>
		</div>

		<div class="well">
			<h4>Where to log time</h4>
			<p>At DevTrack, we try to integrate all of our features together where possible. For this reason there are more than a handful of ways in which you can log time. Here we will go through the two most common ways.</p>

			<h5>1) The 'week' page</h5>
			<p>Select '<a href="#"><i class="icon-book"></i> Time</a>' from the projects sidebar and then select <button class="btn btn-mini">History</button> from the time menu bar at the top of the page. If you've found your way correctly then you will encounter a view similar to that shown in <strong>exhibit 2</strong>.</p>
			<p>This view shows all the tasks that have had time logged to them on the week shown. To move forward and back weeks, use the controls at the bottom of the page. To see details of the time logged for a task on a day, click on the box containing the total hours logged on that day for the task.</p>
			<p><strong>e.g.</strong> To view all the time logged on monday for your task 'Create a new time tracking interface' you would click on the '1' in exhibit 2.</p>
			<p>From here you can view more details about the time logged, or even log more time!</p>
			<div class="alert alert-info" style="margin-bottom:0px;">
				<strong>Hint:</strong> Click on any box on the 'week' interface to add time to that day/task </strong>
			</div>
		</div>

		<div class="row-fluid">
			<div class="span8 offset2">
				<?= $this->element('Help/example_tempo') ?>
				<div class="alert alert-info">
					<strong>Exhibit 2:</strong> Time logged for tasks on each day of a certain week.
				</div>
			</div>
		</div>

		<div class="well">
			<h5>2) The 'Log Time' button</h5>
			<p>In a hurry? From any of the time pages, click <button class="btn btn-mini btn-primary">Log time</button>. Located in the top right of the screen, this button will take you directly to the add time page. From here, enter the details (time spent, description, date and associated task) of the time you have spent on the project and click <button class="btn btn-mini btn-primary">Submit</button>.</p>
		</div>
	</div>
</div>
