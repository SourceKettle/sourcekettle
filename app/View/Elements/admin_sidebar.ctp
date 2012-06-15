<?php
    $account = array(
        '.' => array('icon' => 'fullscreen', 'text' => 'Overview'),
        'users' => array('icon' => 'user', 'text' => 'Search Users'),
        'projects' => array('icon' => 'list-alt', 'text' => 'Search Projects'),
        'settings' => array('icon' => 'warning-sign', 'text' => 'System Settings'),
    );
?>
<ul class="well nav nav-list" style="padding: 8px 14px;">
    <li class="nav-header">Administration</li>
    
    <? // Iterate over the sidebar options in $account
    foreach ( $account as $feature => $options ): ?>
    
    <li<?= ($feature == $action) ? ' class="active"' : '' ?>>
        <?=$this->Html->link(
            '<i class="' . (($feature == $action) ? 'icon-white' : '') . 
                ' icon-' . $options['icon'] . '"></i> ' . 
                ((isset($options['text']) ? ucwords($options['text']) : ucwords($feature))),
            array('admin' => true, 'controller' => $feature, 'action' => '.'),
            array('escape' => false)
        )?>
    </li>
    
    <? endforeach; ?>
    
    <li class="divider"></li>
    <li>
        <?=$this->Html->link(
            '<i class="icon-flag"></i> Help',
            array('admin' => true, 'controller' => 'help', 'action' => '.'),
            array('escape' => false)
        )?>
    </li>
</ul>
