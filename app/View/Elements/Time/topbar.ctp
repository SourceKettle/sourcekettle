<?php
    $a1 = $this->request['action'];
?>
<div class="span8">
    <ul class="nav nav-pills">
        <li <? if ($a1=='users') echo 'class="active"'; ?>>
            <?= $this->Html->link('User Stats', array('action' => 'users', 'controller' => 'times', 'project' => $this->params['project'])) ?>
        </li>
        <li <? if ($a1=='log') echo 'class="active"'; ?>>
            <?= $this->Html->link('Complete Log', array('action' => 'log', 'controller' => 'times', 'project' => $this->params['project'])) ?>
        </li>
    </ul>
</div>
<div class="span2">
    <?= $this->Bootstrap->button_link('Log Time', array('action' => 'add', 'project' => $this->params['project']), array("style" => "primary", "class" => "pull-right")) ?>
</div>
