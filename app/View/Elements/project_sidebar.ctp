<?php
    $features = array(
        'view' => array('icon' => 'home', 'text' => 'home'),
        'time' => array('icon' => 'book'),
        'source' => array('icon' => 'pencil'),
        'tasks' => array('icon' => 'file'),
    );
    $admin = array(
        'collaborators' => array('icon' => 'user'),
        'edit' => array('icon' => 'cog', 'text' => 'Settings'),
    );
?>
<ul class="well nav nav-list" style="padding: 8px 14px;">
    <li class="nav-header">Project Features</li>
    
    <? // Iterate over the sidebar options in $features
    foreach ( $features as $feature => $options ): ?>
    
    <li<?= ($feature == $action) ? ' class="active"' : '' ?>>
        <?=$this->Html->link(
            $this->Bootstrap->icon($options['icon'], (($feature == $action) ? 'white' : 'black')).' '. 
                ((isset($options['text']) ? ucwords($options['text']) : ucwords($feature))),
            array('controller' => 'projects', 'action' => $feature, 'project' => $project),
            array('escape' => false)
        )?>
    </li>
    
    <? endforeach; ?>
    
    <li class="nav-header">Administration</li>
    
    <? // Iterate over the sidebar options in $admin
    foreach ( $admin as $feature => $options ): ?>
    
    <li<?= ($feature == $action) ? ' class="active"' : '' ?>>
        <?=$this->Html->link(
            $this->Bootstrap->icon($options['icon'], (($feature == $action) ? 'white' : 'black')).' '. 
                ((isset($options['text']) ? ucwords($options['text']) : ucwords($feature))),
            array('controller' => 'projects', 'action' => $feature, 'project' => $project),
            array('escape' => false)
        )?>
    </li>
    
    <? endforeach; ?>
    
    <li class="divider"></li>
    <li>
        <?=$this->Html->link(
            $this->Bootstrap->icon('flag').' Help',
            array('controller' => 'help', 'action' => 'project'),
            array('escape' => false)
        )?>
    </li>
</ul>
