<ul class="well nav nav-list">
    <li class="nav-header">Project Features</li>
    <li class="active">
        <a href="#">
            <i class="icon-white icon-home"></i> Home
        </a>
    </li>
    <li>
        <?=$this->Html->link(
            '<i class="icon-book"></i> Time',
            array('action' => 'time', $id),
            array('escape' => false)
        )?>
    </li>
    <li>
        <?=$this->Html->link(
            '<i class="icon-pencil"></i> Source',
            array('action' => 'source', $id),
            array('escape' => false)
        )?>
    </li>
    <li>
        <?=$this->Html->link(
            '<i class="icon-file"></i> Tasks',
            array('action' => 'tasks', $id),
            array('escape' => false)
        )?>
    </li>
    <li class="nav-header">Administration</li>
    <li>
        <?=$this->Html->link(
            '<i class="icon-user"></i> Collaborators',
            array('action' => 'collaborators', $id),
            array('escape' => false)
        )?>
    </li>
    <li>
        <?=$this->Html->link(
            '<i class="icon-cog"></i> Settings',
            array('action' => 'edit', $id),
            array('escape' => false)
        )?>
    </li>
    <li class="divider"></li>
    <li>
        <?=$this->Html->link(
            '<i class="icon-flag"></i> Help',
            array('controller' => 'help', 'action' => 'project'),
            array('escape' => false)
        )?>
    </li>
</ul>
