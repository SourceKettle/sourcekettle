<?php
    // How full will the mini graph be?
    $uTotal = $time['Time']['mins']['mins'] + (60 * $time['Time']['mins']['hours']);
    if ($uTotal <= 30) {
        $uTotal = array(10, 90);
    } elseif ($uTotal <= 60) {
        $uTotal = array(20, 80);
    } elseif ($uTotal <= 120) {
        $uTotal = array(30, 70);
    } elseif ($uTotal <= 240) {
        $uTotal = array(40, 60);
    } elseif ($uTotal <= 480) {
        $uTotal = array(50, 50);
    } elseif ($uTotal <= 960) {
        $uTotal = array(60, 40);
    } elseif ($uTotal <= 1920) {
        $uTotal = array(70, 30);
    } elseif ($uTotal <= 3684) {
        $uTotal = array(80, 20);
    } else {
        $uTotal = array(90, 10);
    }
?>
<div class="span6">
    <div class="well">
        <span>
            <?= $this->Html->link($this->Gravatar->image($time['User']['email'], array('size' => 40), array('alt' => $time['User']['name'])), array('controller' => 'users', 'action' => 'view', $time['User']['id']), array('escape' => false)) ?>
            <?= $this->Html->link($time['User']['name'], array('controller' => 'users', 'action' => 'view', $time['User']['id'])) ?>
            logged
            <?= $this->Bootstrap->label($time['Time']['mins']['hours'].'h '.$time['Time']['mins']['mins'].'m', "warning") ?>
            <?= $this->Time->timeAgoInWords($time['Time']['created']) ?>
        </span>
        <span class="pull-right">
            <?= $this->GoogleChart->create()->setType('pie')->setSize(40, 40)->addData($uTotal)->setMargins(0, 0, 0, 0)//->setContainerSize(40, 40) ?>
        </span>
    </div>
</div>