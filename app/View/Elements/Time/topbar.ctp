<?php
    $a1 = $this->request['action'];
?>
<div class="span10">
    <ul class="nav nav-pills">
        <li <? if ($a1=='users') echo 'class="active"'; ?>>
            <?= $this->Html->link('User Stats', array('action' => 'users', 'controller' => 'times', 'project' => $this->params['project'])) ?>
        </li>
        <li <? if ($a1=='log') echo 'class="active"'; ?>>
            <?= $this->Html->link('Complete Log', array('action' => 'log', 'controller' => 'times', 'project' => $this->params['project'])) ?>
        </li>
        <li <? if ($a1=='add') echo 'class="active pull-right"'; else echo 'class="pull-right"'; ?>>
            <?= $this->Html->link('<strong>Log Time</strong>', array('action' => 'add', 'controller' => 'times', 'project' => $this->params['project']), array('escape' => false)) ?>
        </li>
    </ul>
</div>
