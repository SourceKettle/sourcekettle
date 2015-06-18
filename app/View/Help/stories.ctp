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
	            <li>
				<strong>The subject:</strong> A short, structured piece of text describing what the user wants to do; should be of the form "As a &lt;type of user&gt; I want &lt;a feature or capability&gt; So that &lt;reason why it would be useful&gt;". 
				<ul><li><small>Example: <em>As a supervillain in Metropolis, I want my own megacorporation, so that I can secretly research ways to kill Superman</em></small></li></ul>
			</li>
		    <li>
				<strong>The description:</strong> A plain text description with more detailed information about the story. 
				<ul><li><small>Example: <em>Superman is really bugging me these days as he keeps thwarting my evil plots to take over the world. I really need to start researching ways to kill him and begin my reign of tyranny.</em></small></li></ul>
			</li>
		    <li>
			<strong>The acceptance criteria:</strong> A text description of how the system can be tested to ensure that the feature works as per the user's requirements. You may wish to use <a href="https://github.com/cucumber/cucumber/wiki/Gherkin">Gherkin</a> syntax if you are using behavioural-driven testing tools to automatically test the software. 
				<ul><li><small>Example: <em>When I walk into the lobby of my massive corporate headquarters, I should see a massive gold statue of me spinning the world on my finger like a basketball. This way I will know I have my own megacorporation because that's totally how this works.</em></small></li></ul>
			</li>
	          </ol>
	        </p>

		<p>
			As well as being scheduled into <?=$this->Html->link(__("milestones"), array("controller" => "help", "action" => "milestones"))?>, <?=$this->Html->link(__("tasks"), array("controller" => "help", "action" => "tasks"))?> (things for the development team to do) may be linked to a story. As the tasks are completed, the story's completion percentage will increase based on the number of story points each task is worth. <strong>Note: it is important to make sure you have a story point estimate for tasks attached to a story!</strong>
		</p>
	</div>
</div>
<div class="row-fluid">
	<div class="well example span12">
		<h4>Example story</h4>
		<?=$this->element('Story/block', array('span' => 11, 'story' => array(
			"Story" => array(
				"public_id" => 0,
				"subject" => "As a SourceKettle user, I want to be able to create user stories so that I can plan project features with a user focus",
				"description" => "I care about user experience, so I want to make sure I get it right!",
				"acceptance_criteria" => "",
			),
			"Project" => array(
				"name" => "example_project",
			),
			"Task" => array(
				array("story_points" => 5, "TaskStatus" => array("name" => "open")),
				array("story_points" => 12, "TaskStatus" => array("name" => "closed")),
			),
		)))?>
	
	</div>
</div>

	
<div class="row-fluid">
	<div class="well">
	
	        <h4>Story blocks</h4>
	        <p>
	          Wherever stories are listed, such as the story list or milestone board, they are displayed as a block containing:
	          <ul>
	            <li>A progress bar, based on how many story points' worth of tasks have been resolved</li>
	            <li>The subject</li>
	            <li>The description</li>
	            <li>(when on a milestone board) A list of tasks attached to the story</li>
	          </ul>
	        </p>
		<p>
			There are also links to edit, delete, or add a task to the story. When viewing the story on the milestone board, any tasks that are attached to the story but NOT the milestone will be indicated with a dashed border and will be greyed out slightly.
		</p>
	</div>
</div>

<div class="row-fluid">
	
	<div class="well">
	
	        <h4>Story detail view</h4>
	        <p>
	          Clicking on a story brings up a more detailed overview.  From this view you can:
	          <ul>
	            <li><strong>Edit the story:</strong> Change its subject, description and acceptance criteria</li>
		    <li><strong>List tasks:</strong> See a list of all tasks attached to the story, and change their assignee and other details</li>
	          </ul>
	        </p>
	</div>
</div>
