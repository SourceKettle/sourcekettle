
<div class="span<?=$span?><?= (@$grey) ? ' greyOut':'' ?>">
	<h3><?=__("Next Milestone")?></h3>
    <hr />
    <ul class="unstyled">
        <? if ($milestone) : ?>
        <li><strong><?= $this->Html->link(
            $milestone['Milestone']['subject'],
            array(
                'project'=>$project['Project']['name'],
                'controller'=>'milestones',
                'action'=>'view',
                $milestone['Milestone']['id']
            )) ?></strong></li>
        <br>
        <li>Due: <?= h($milestone['Milestone']['due']) ?></li>
        <?= $this->Bootstrap->progress(array("width" => (int) $milestone['Progress']['tasksPct'], "striped" => true)) ?>
        <? endif; ?>
    </ul>
</div>

