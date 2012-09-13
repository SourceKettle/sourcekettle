<?= $this->Bootstrap->page_header($user['User']['name']); ?>

<div class="row">
    <dl class="dl-horizontal span6">
        <dt>
        Email address
        </dt>
        <dd>
            <?= $user['User']['email'] ?>
        </dd>
        <dt>
        User registered
        </dt>
        <dd>
            <?= $this->Time->timeAgoInWords($user['User']['created'], 'Y-m-d') ?>
        </dd>
    </dl>
</div>

<div class="row">
    <?php
    // Loop through all the projects that a user has access to
    if (empty($projects)) {
        echo "<p class='span12'>This user has no public projects</p>";
    } else {
        foreach ($projects as $project):
            ?>
            <div class="span4">
                <div class="well project-well">
                    <h3 class="project-title"><?= $this->Html->link($project['Project']['name'], array('controller' => 'projects', 'action' => '.', 'project' => $project['Project']['name']), array('class' => 'project-link')) ?>
                        <span class="pull-right"><?= $this->Bootstrap->icon((($project['Project']['public']) ? 'globe' : 'lock'), 'black') ?></span></h3>
                    <p class="project-desc"><?= $project['Project']['description'] ?></p>
                    <p class="project-time">Last Modified: <?= $this->Time->timeAgoInWords($project['Project']['modified']) ?></p>
                </div>
            </div>
    <?php endforeach;
    } ?>
</div>


