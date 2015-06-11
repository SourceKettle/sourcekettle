
<?=$this->Html->css('tasks', null, array ('inline' => false))?>
<?=$this->Html->css('stories', null, array ('inline' => false))?>
<?=$this->Html->script("stories", array ('inline' => false))?>
<?=$this->Html->script("tasks", array ('inline' => false))?>
<?=$this->Html->css('/prettify/prettify', null, array ('inline' => false))?>
<?=$this->Html->script('/prettify/prettify', array('block' => 'scriptBottom'))?>
<?=$this->Html->script('/prettify/lang-gherkin', array('block' => 'scriptBottom'))?>
<?=$this->Html->scriptBlock("prettyPrint()", array('inline' => false))?>

<div class="row">
<div class="well span8 offset2">
	<h3 class="story-title story-subject-text"> #<?=h($story['Story']['public_id'])?>: <?=h($story['Story']['subject'])?></h3>
	<button type="button" class="close edit"><?= $this->Bootstrap->icon('pencil'); ?></button>
	<span class="edit-form input-append hide">
		<?= $this->Form->textarea("subject", array("rows" => 1)); ?>
		<?= $this->Bootstrap->button(__("Update"), array("style" => "primary")); ?>
	</span>
	<p class="story-description-text">
	<? if ($story['Story']['as-a']) {?>
		<ul>
		<li><?=__("<strong>As a:</strong> %s", $story['Story']['as-a'])?></li>
		<li><?=__("<strong>I want:</strong> %s", $story['Story']['i-want'])?></li>
		<li><?=__("<strong>So that:</strong> %s", $story['Story']['so-that'])?></li>
		</ul>
	<? } else { ?>
		<?=h($story['Story']['description'])?>
	<? } ?>
	</p>
	<button type="button" class="close edit"><?= $this->Bootstrap->icon('pencil'); ?></button>

	<span class="edit-form input-append hide">
		<?= $this->Form->textarea("description", array("rows" => 1)); ?>
		<?= $this->Bootstrap->button(__("Update"), array("style" => "primary")); ?>
	</span>
</div>
</div>

<div class="row">
<div class="well span8 offset2">
	<h3 class="story-title"><?=__("Acceptance criteria")?></h3>
	<pre class="story-acceptance-criteria prettyprint lang-gherkin"><?= h($story['Story']['acceptance_criteria']) ?></pre>
	<button type="button" class="close edit"><?= $this->Bootstrap->icon('pencil'); ?></button>
	<span class="edit-form input-append hide">
		<?= $this->Form->textarea("acceptance_criteria", array("rows" => 1)); ?>
		<?= $this->Bootstrap->button(__("Update"), array("style" => "primary")); ?>
	</span>
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

