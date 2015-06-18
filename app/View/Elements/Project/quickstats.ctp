
<? if ($numTeamCollab > 0) {
	$collabsLink = $this->Html->link(  __('%d '.Inflector::pluralize(__('team'), $numTeamCollab).' and %d '.Inflector::pluralize(__('user'), $numCollab), $numTeamCollab, $numCollab), array('controller' => 'collaborators', 'action' => '*', 'project' => $project['Project']['name']));
} else {
	$collabsLink = $this->Html->link(  __('%d '.Inflector::pluralize(__('user'), $numCollab), $numCollab), array('controller' => 'collaborators', 'action' => '*', 'project' => $project['Project']['name']));
} ?>

<div class="span<?=$span?><?= (@$grey) ? ' greyOut':'' ?>">
    <h3><?= __("Quick Stats")?></h3>
    <hr />
    <ul class="unstyled">
        <li><strong><?=$collabsLink?></strong> <?=__("are working on this project")?>.</li>
        <li><?= __("Last activity was")?> <strong><?= $this->Time->timeAgoInWords($project['Project']['modified']) ?></strong>.</li>
	
	<? if ($sourcekettle_config['Features']['task_enabled']['value']) {?>
	<li><?=__('Story points complete: <strong>%d / %d (%d%%)</strong>', $numberOfFinishedPoints, $numberOfPointsTotal, $pctFinishedPoints)?></li>
	<? } ?>
	<? if ($sourcekettle_config['Features']['time_enabled']['value']) {?>
	<li><?=__('Time logged to project: <strong>%s</strong>', TimeString::renderTime($timeTotal, 's'))?></li>
	<? } ?>
    </ul>
</div>
	
