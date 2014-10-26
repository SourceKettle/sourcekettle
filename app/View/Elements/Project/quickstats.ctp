
<div class="span<?=$span?><?= (@$grey) ? ' greyOut':'' ?>">
    <h3><?= __("Quick Stats")?></h3>
    <hr />
    <ul class="unstyled">
        <li><strong><?= $this->Html->link($numCollab . " " . Inflector::pluralize(__('user'), $numCollab), array('controller' => 'collaborators', 'action' => '*', 'project' => $project['Project']['name']))?></strong> <?=__("are working on this project")?>.</li>
        <li><?= __("Last activity was")?> <strong><?= $this->Time->timeAgoInWords($project['Project']['modified']) ?></strong>.</li>
    </ul>
</div>
	
