<?php
/**
 *
 * View class for APP/help/time for the SourceKettle system
 * Display the help page for logging time
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          https://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Help
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('TimeString', 'Time');
$this->Html->css('time.tempo', null, array ('inline' => false));
$this->Html->css('pages/help', null, array ('inline' => false));
$this->Html->script('help', array('inline' => false));

// Fake example data
$users = array(
	array(
	'User' => array(
		'id' => 0,
		'email' => 'andy@example.org',
		'name' => 'Andy Newton',
	),
	'Time' => array(
		'time' => TimeString::renderTime(3000),
	)),
	array(
	'User' => array(
		'id' => 0,
		'email' => 'phill@example.org',
		'name' => 'Phill Whittlesea',
	),
	'Time' => array(
		'time' => TimeString::renderTime(3020),
	)),
	array(
	'User' => array(
		'id' => 0,
		'email' => 'chris@example.org',
		'name' => 'Chris Bulmer',
	),
	'Time' => array(
		'time' => TimeString::renderTime(1020),
	)),
);

$project = array('Project' => array(
	'id' => '0',
	'name' => 'SourceKettle',
));

$start = new DateTime('last monday', new DateTimeZone('UTC'));
$weekTimes = array(
	'dates' => array(),
	'tasks' => array(
		array(
			'Task' => array('id' => 0, 'public_id' => 0, 'subject' => null,),
			'users' => array(
				array(
					'User' => array('id' => 0, 'name' => 'Andy Newton', 'email' => 'andy@example.org'),
					'times_by_day' => array(1 => array(array('Time' => array('id' => 0, 'date' => $start->format('Y-m-d'), 'description' => '', 'mins' => 10, 'minutes' => TimeString::renderTime(10))))),
				),
			),
		),
		array(
			'Task' => array('id' => 1, 'public_id' => 2, 'subject' => 'Create a new time tracking system',),
			'users' => array(
				array(
					'User' => array('id' => 0, 'name' => 'Phill Whittlesea', 'email' => 'phill@example.org'),
					'times_by_day' => array(1 => array(array('Time' => array('id' => 0, 'date' => $start->format('Y-m-d'), 'description' => '', 'mins' => 80, 'minutes' => TimeString::renderTime(10))))),
				),
			),
		),
	),
	'totals' => array(1 => 90, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0),
);
for ($i = 1; $i <= 7; $i++) {
	$day = clone $start;
	$day->add(new DateInterval('P' . ($i - 1) . 'D'));
	$weekTimes['dates'][$i] = $day;
}

$thisWeek = (int)$start->format('w');
$prevWeek = ((int)$start->format('w') - 1) % 52;
$nextWeek = ((int)$start->format('w') + 1) % 52;
$thisYear = (int)$start->format('Y');
$prevYear = (int)$start->format('Y') - 1;
$nextYear = (int)$start->format('Y') + 1;
?>

<div class="row-fluid">
	<div class="well">
		<h3>Logging time</h3>
		<p>On this help page, how to log, edit and view time is discussed. But first, what makes up a piece of logged time?</p>
	</div>
</div>

<div class="row-fluid">
	<div class="well">
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
	</div>
</div>
	
<div class="row-fluid">
	<div class="well">
		<h4>The 'Project Summary' page</h4>
		<p>Upon first selecting '<a href="#"><i class="icon-book"></i> Time</a>' from the project sidebar, you will be presented with what we call a 'project summary' page. Depending upon the state of your project you may see different things.</p>
		<p>If you (or a team member) has previously logged time to the project you will be presented with a fancy table and pie chart showing the breakdown of users' efforts (<strong>exhibit 1</strong>).</p>
		<p>If you have yet to log time to the project, just click the <button class="btn btn-mini btn-primary">Log time</button> button!</p>

		<p>Next to each user's name, you can see a <a href="#" onclick="return false;">[view time log]</a> link, which will show a complete history of the user's time contributions to the project.</p>
	</div>
</div>

<div class="row-fluid">
	<div class="offset1 span10 example">
                <?=$this->element('Time/breakdown_full', array('users' => $users, 'project' => $project))?>
	</div>
</div>

<div class="row-fluid">
	<div class="offset1 span10 alert alert-info">
		<strong>Exhibit 1:</strong> Time pie chart.
	</div>
</div>
	
<div class="row-fluid">
	<div class="well">
		<h4>The 'Timesheets' page</h4>
		<p>Select '<a href="#"><i class="icon-book"></i> Time</a>' from the projects sidebar and then select <button class="btn btn-mini">History</button> from the time menu bar at the top of the page. If you've found your way correctly then you will encounter a view similar to that shown in <strong>exhibit 2</strong>.</p>
		<p>This view shows all the tasks that have had time logged to them on the week shown, and who logged how much time. To move forward and back weeks, use the controls at the bottom of the page. To see details of the time logged for a user/task on a day, click on the box containing the total hours logged on that day for the task.</p>
		<p>The <button class="btn btn-mini"><i class="icon-download"></i> Download CSV</button> button lets you get the same data as CSV, for e.g. importing into a spreadsheet.</p>
		<p><strong>e.g.</strong> To view all the time you logged on monday for your task 'Create a new time tracking interface' you would click on the '1' in exhibit 2.</p>
		<p>From here you can view more details about the time logged, or even log more time!</p>
		<div class="alert alert-info" style="margin-bottom:0px;">
			<strong>Hint:</strong> Click on any box on the 'Timesheets' interface to add time to that day/task </strong>
		</div>
	</div>
</div>

<div class="row-fluid">
	<div class="span8 offset2 example">
               <?= $this->element('Time/tempo', array(
			'weekTimes' => $weekTimes,
			'startDate' => $weekTimes['dates'][1],
			'endDate' => $weekTimes['dates'][7],
			'project' => $project,
			'thisWeek' => $thisWeek,
			'prevWeek' => $prevWeek,
			'nextWeek' => $nextWeek,
			'thisYear' => $thisYear,
			'prevYear' => $prevYear,
			'nextYear' => $nextYear,
		)) ?>
	</div>
</div>
<div class="row-fluid">
	<div class="span8 offset2 alert alert-info">
		<strong>Exhibit 2:</strong> Time logged for tasks on each day of a certain week.
	</div>
</div>

<div class="row-fluid">
	<div class="well">
		<h4>Where to log time</h4>
		<p>At SourceKettle, we try to integrate all of our features together where possible. For this reason there are more than a handful of ways in which you can log time:</p>
		<ol>
			<li>The time 'Project Summary' and 'Timesheets' pages both have a <button class="btn btn-mini btn-primary">Log time</button> button at the top right</li>
			<li>The project overview page has a <?=$this->Bootstrap->icon('time')?><a href="#" onclick="return false;">Log time</a> link</li>
			<li>When viewing a task, you can click the <button class="btn btn-mini">Log time</button> button to log time to that task</li>
		</ol>

	</div>
</div>
