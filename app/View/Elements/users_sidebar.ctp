<?php
    $account = array(
        'editdetails' => array('icon' => 'user', 'text' => 'Basic details'),
        'editpassword' => array('icon' => 'lock', 'text' => 'Change password'),
        'delete' => array('icon' => 'remove', 'text' => 'Delete account'),
    );
    $sshkey = array(
        'addkey' => array('icon' => 'plus-sign', 'text' => 'Add key'),
        'deletekey' => array('icon' => 'minus-sign', 'text' => 'Edit keys'),
    );
?>
<ul class="well nav nav-list" style="padding: 8px 14px;">
    <li class="nav-header">Your account</li>
    
    <? // Iterate over the sidebar options in $account
    foreach ( $account as $feature => $options ): ?>
    
    <li<?= ($feature == $action) ? ' class="active"' : '' ?>>
        <?=$this->Html->link(
            '<i class="' . (($feature == $action) ? 'icon-white' : '') . 
                ' icon-' . $options['icon'] . '"></i> ' . 
                ((isset($options['text']) ? ucwords($options['text']) : ucwords($feature))),
            array('controller' => 'users', 'action' => $feature),
            array('escape' => false)
        )?>
    </li>
    
    <? endforeach; ?>
    
    <li class="nav-header">SSH Keys</li>
    
    <? // Iterate over the sidebar options in $sshkey
    foreach ( $sshkey as $feature => $options ): ?>
    
    <li<?= ($feature == $action) ? ' class="active"' : '' ?>>
        <?=$this->Html->link(
            '<i class="' . (($feature == $action) ? 'icon-white' : '') . 
                ' icon-' . $options['icon'] . '"></i> ' . 
                ((isset($options['text']) ? ucwords($options['text']) : ucwords($feature))),
            array('controller' => 'users', 'action' => $feature),
            array('escape' => false)
        )?>
    </li>
    
    <? endforeach; ?>
    
    <li class="divider"></li>
    <li>
        <?=$this->Html->link(
            '<i class="icon-flag"></i> Help',
            array('controller' => 'help', 'action' => 'user'),
            array('escape' => false)
        )?>
    </li>
</ul>
