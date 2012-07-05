<?php
    $a1 = $this->request['action'];
?>
<div class="span10">
    <ul class="nav nav-pills">
        <li class="dropdown active pull-right">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#"><?= $this->Bootstrap->icon('random', 'white') ?> <strong>Branch: <?= $branch ?></strong><b class="caret"></b></a>
            <ul class="dropdown-menu">
                <? foreach ($branches as $branch) : ?>
                    <li><?= $this->Html->link($branch, array('project' => $project['Project']['name'], 'action' => 'commits', $branch)) ?></li>
                <? endforeach; ?>
            </ul>
        </li>
        <li <? if ($a1=='tree') echo 'class="active"'; ?>>
            <?= $this->Html->link('Code', array('action' => 'tree', 'controller' => 'source', 'project' => $this->params['project'])) ?>
        </li>
        <li <? if ($a1=='commits') echo 'class="active"'; ?>>
            <?= $this->Html->link('Commits', array('action' => 'commits', 'controller' => 'source', 'project' => $this->params['project'])) ?>
        </li>
        <li class="pull-right<? if ($a1=='gettingStarted') echo ' active'; ?>">
            <?= $this->Html->link('Get The Code', array('action' => 'gettingStarted', 'controller' => 'source', 'project' => $this->params['project'])) ?>
        </li>
        <li class="pull-right<? if ($a1=='downloads') echo ' active'; ?>">
            <?= $this->Html->link('Downloads', array('action' => 'downloads', 'controller' => 'source', 'project' => $this->params['project'])) ?>
        </li>
    </ul>
</div>
