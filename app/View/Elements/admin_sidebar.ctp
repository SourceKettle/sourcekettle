<?php
    $admin = array(
        '.' => array('icon' => 'fullscreen', 'text' => 'Overview'),
        'settings' => array('icon' => 'warning-sign', 'text' => 'System Settings'),
    );
    $user_admin = array(
        'users' => array('icon' => 'search', 'text' => 'Search Users', 'action' => 'index'),
        'useradd' => array('icon' => 'user', 'text' => 'Add a User', 'action' => 'add'),
    );
    $proj_admin = array(
        'projects' => array('icon' => 'search', 'text' => 'Search Projects', 'action' => 'index'),
        'projectadd' => array('icon' => 'file', 'text' => 'Add a Project', 'action' => 'add'),
    );
?>
<ul class="well nav nav-list" style="padding: 8px 14px;">
    <li class="nav-header">Administration</li>

    <? // Iterate over the sidebar options in $admin
    foreach ( $admin as $feature => $options ):
        $text = ucwords((isset($options['text'])) ? $options['text'] : $feature); ?>
    <li<?= ($feature == $action) ? ' class="active"' : '' ?>>
        <?=$this->Html->link(
            $this->Bootstrap->icon($options['icon'], ($feature == $action) ? 'white' : 'black').' '.$text,
            array('admin' => true, 'controller' => $feature, 'action' => '.'),
            array('escape' => false)
        )?>
    </li>

    <? endforeach; ?>

    <li class="nav-header">User Admin</li>

    <? // Iterate over the sidebar options in $user_admin
    foreach ( $user_admin as $feature => $options ):
        $text = ucwords((isset($options['text'])) ? $options['text'] : $feature); ?>
    <li<?= ($feature == $action) ? ' class="active"' : '' ?>>
        <?=$this->Html->link(
            $this->Bootstrap->icon($options['icon'], ($feature == $action) ? 'white' : 'black').' '.$text,
            array('admin' => true, 'controller' => 'users', 'action' => $options['action']),
            array('escape' => false)
        )?>
    </li>

    <? endforeach; ?>

    <li class="nav-header">Project Admin</li>

    <? // Iterate over the sidebar options in $proj_admin
    foreach ( $proj_admin as $feature => $options ):
        $text = ucwords((isset($options['text'])) ? $options['text'] : $feature); ?>
    <li<?= ($feature == $action) ? ' class="active"' : '' ?>>
        <?=$this->Html->link(
            $this->Bootstrap->icon($options['icon'], ($feature == $action) ? 'white' : 'black').' '.$text,
            array('admin' => true, 'controller' => 'projects', 'action' => $options['action']),
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
