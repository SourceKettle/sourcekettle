<?php
    $a1 = $this->request['action'];
?>
<div class="span10">
    <ul class="nav nav-pills">
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
