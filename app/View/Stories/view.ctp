
<?=$this->Html->css('tasks', null, array ('inline' => false))?>
<?=$this->Html->css('stories', null, array ('inline' => false))?>
<?=$this->Html->script("stories", array ('inline' => false))?>
<?=$this->Html->script("tasks", array ('inline' => false))?>
<?=$this->Html->css('/prettify/prettify', null, array ('inline' => false))?>
<?=$this->Html->script('/prettify/prettify', array('block' => 'scriptBottom'))?>
<?=$this->Html->script('/prettify/lang-gherkin', array('block' => 'scriptBottom'))?>
<?=$this->Html->scriptBlock("prettyPrint()", array('inline' => false))?>
<?=$this->Task->allDropdownMenus() ?>

<div class="row-fluid">
	<?= $this->element('Story/topbar_view', array('name' => $story['Story']['subject'], 'id' => $story['Story']['public_id'])) ?>
</div>

<div class="row">
<div class="well span8 offset2">
	<h3 class="story-title story-subject-text"> #<?=h($story['Story']['public_id'])?>: <?=h($story['Story']['subject'])?></h3>
	<p class="story-description-text">
		<?=h($story['Story']['description'])?>
	</p>

</div>
</div>

<div class="row">
<div class="well span8 offset2">
	<h3 class="story-title"><?=__("Acceptance criteria")?></h3>
	<pre class="story-acceptance-criteria prettyprint lang-gherkin"><?= h($story['Story']['acceptance_criteria']) ?></pre>
</div>
</div>

<div class="row">
<div class="span8 offset2">
	<ul class="well sprintboard-droplist">
	<h2><?=__("Tasks linked to this story")?></h2>
	<hr/>
		<? foreach ($story['Task'] as $task) {
			// TODO this is a total mess but cba to figure it out right now
			$task = array('Task' => $task);
			foreach (array('Project', 'Owner', 'Assignee', 'Milestone', 'TaskType', 'TaskStatus', 'TaskPriority') as $x) {
				$task[$x] = $task['Task'][$x];
				unset($task['Task'][$x]);
			}
	
			echo $this->element('Task/lozenge', array('task' => $task, 'span' => 12, 'draggable' => true));
		} ?>
	</ul>
</div>
</div>

