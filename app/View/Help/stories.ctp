<?php
/**
 *
 * View class for APP/help/stories for the SourceKettle system
 * Display the help page for user stories
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2015
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Help
 * @since         SourceKettle v 1.7
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$this->Html->css('time.tempo', null, array ('inline' => false));
$this->Html->css('pages/help', null, array ('inline' => false));
$this->Html->css('stories', null, array ('inline' => false));
$this->Html->script('help', array('inline' => false));

// Fake example data
$project = array('Project' => array(
	'id' => '0',
	'name' => 'SourceKettle',
));

$stories = array(
	array(
		'Story' => array(
			'id' => 0,
			'subject' => 'Do something',
			'story_status_id' => 1,
			'story_priority_id' => 2,
			'story_type_id' => 1,
			'public_id' => 0,
		),
		'StoryType' => array(
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
		'Story' => array(
			'id' => 0,
			'subject' => 'Do something else',
			'story_status_id' => 2,
			'story_priority_id' => 3,
			'story_type_id' => 2,
			'public_id' => 0,
		),
		'StoryType' => array(
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
		'Story' => array(
			'id' => 0,
			'subject' => 'Do something',
			'story_status_id' => 4,
			'story_priority_id' => 4,
			'story_type_id' => 1,
			'dependenciesComplete' => true,
			'public_id' => 0,
		),
		'DependsOn' => array(0 => array('fish')),
		'StoryType' => array(
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
	<div class="well">
		<h3>User Stories</h3>
		<p>
		User stories are a great way for developers and (future!) end-users to agree what the system should <em>actually do</em>. For the end user, this is good because they can articulate what will actually be useful for them; for the developer, this is good because they can create a useful system that people want to use!
		</p>
	</div>
	<div class="well">
	        <h3>Story contents</h3>
	        <p>
		  Stories consist of:
	          <ol>
	            <li><strong>The subject:</strong> A brief but informative description of the story - such as </li>
	            <li><strong>The description:</strong> The full story: this can be any text, but should be of the form "As a ... I want ... So that ..."</li>
	          </ol>
	        </p>
		
		<h4>Story examples</h4>
		<p>
		<?=$this->element('Story/block', array('story' => array(
			"Story" => array(
				"public_id" => 0,
				"subject" => "User stories",
				"description" => "As a SourceKettle user, I want to be able to create user stories so that I can plan project features with a user focus",
			),
			"Project" => array(
				"name" => "example_project",
			),
			"Task" => array(),
		)))?>
		</p>
	
	</div>
</div>

<div class="row-fluid">
	<div class="well">
	        <h4>The story display board</h4>
	        <p>
	          If your project doesn't have any stories yet, click the <button class="btn btn-mini btn-primary">Create Story</button> button to get started.
	        </p>
	        <p>
	          Once you've created a story (or a few stories - treat yourself!), they will show up in the story display board.  This is a master list of all stories for the project,
	          which you can filter by:
	          <ul>
	            <li>Assignment (who will be actually doing the work?)</li>
				<li>Status (what is currently happening?)</li>
				<li>Priority (how urgent is it?)</li>
				<li>Milestone (which milestone, if any, is the story part of?)</li>
	          </ul>
	        </p>
	
	        <p>
	          The panel displays a list of stories (matching your filter selections) in order of priority.  Click on a story to display the full detail page for that story.
	        </p>
	</div>
</div>
	
<div class="row-fluid">
	<div class="span8 offset2 example">
	        <div class="row-fluid">
	            <?= $this->element('Story/topbar_filter', array(
					'collaborators' => array(),
					'selected_statuses' => array(),
					'selected_priorities' => array(),
					'selected_types' => array(),
					'selected_milestones' => array(),
					'milestones' => array('open' => array(), 'closed' => array()),
				)) ?>
	        </div>
		<div class="row-fluid well col">
	            <h2><?=__("Story list")?></h2>
	            <hr />
		    <ul class="sprintboard-droplist">
		    <? foreach ($stories as $story) {
			  echo $this->element('Story/lozenge', array('story' => $story));
		    } ?>
		    </ul>
		</div>
	</div>
</div>

<div class="row-fluid">
	<div class="alert alert-info span8 offset2">
	        <strong>Exhibit 2:</strong> Story filtering controls
	</div>
</div>
	
<div class="row-fluid">
	<div class="well">
	
	        <h4>Story lozenges</h4>
	        <p>
	          Wherever stories are listed, such as the display board or milestone board, they are displayed as lozenges containing a brief overview of the story.  At a glance, you can see:
	          <ul>
	            <li><strong>Story type:</strong> Indicated by the coloured strip on the left edge of the lozenge</li>
	            <li><strong>Story ID and subject:</strong> Displayed as text</li>
	            <li><strong>Priority:</strong> A black-and-white indicator with icon</li>
	            <li><strong>Status:</strong> A coloured indicator showing the current status</li>
	            <li><strong>Milestone:</strong> A milestone icon (<span class="label"><i class="icon-road"></i></span>) appears if the story is attached to one, click the icon to view the milestone</li>
	            <li><strong>Dependencies:</strong> A red 'D' indicates the story has incomplete dependencies, a green 'D' indicates all dependencies complete; no 'D' indicates no dependencies.</li>
	            <li><strong>Assignee:</strong> Displayed as the user's gravatar image</li>
	          </ul>
	        </p>
	
	</div>
</div>

<div class="row-fluid">
	
	<div class="well">
	
	        <h4>Story detail view</h4>
	        <p>
	          Clicking on a story brings up a more detailed overview.  From this view you can:
	          <ul>
	            <li><strong>Edit the story:</strong> Change its details</li>
	            <li><strong>(Re-)assign the story:</strong> Set the asignee</li>
	            <li><strong>Change the status:</strong> This depends on the current status and whether you are assigned - the topbar will contain an appropriate button to e.g. close or re-open the story</li>
	          </ul>
	        </p>
	
	        <p>
	          If the story is open and assigned to you, you can log time to the story from here.
	        </p>
	
	</div>
</div>
