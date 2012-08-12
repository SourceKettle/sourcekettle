<?php
    $a1 = $this->request['action'];
?>
<div class="span8">
    <ul class="nav nav-pills">
        <li <? if ($a1=='history') echo 'class="active"'; ?>>
            <?= $this->Html->link('Complete History', array('action' => 'history', 'controller' => 'times', 'project' => $this->params['project'])) ?>
        </li>
        <li <? if ($a1=='users') echo 'class="active"'; ?>>
            <?= $this->Html->link('User Stats', array('action' => 'users', 'controller' => 'times', 'project' => $this->params['project'])) ?>
        </li>
    </ul>
</div>
<div class="span2">
    <?= $this->Bootstrap->button_link('Log Time', '#addTimeModal', array('data-toggle' => 'modal', 'style' => 'primary', 'class' => 'pull-right')) ?>
    <?= $this->element('Time/modal_add') ?>
</div>
