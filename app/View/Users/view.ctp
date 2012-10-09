<?= $this->Bootstrap->page_header($user['User']['name']); ?>

<div class="row">
    <div class="span3">
        <?= $this->Gravatar->image(
            $user['User']['email'],
            array('size' => 200),
            array('alt' => $user['User']['name'])
        ) ?>
    </div>
    <dl class="dl-horizontal span9 pull-right">
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
<hr>
<div class="row">
    <?php
    // Loop through all the projects that a user has access to
    if (empty($projects)) {
        echo "<h4 class='span12'>This user has no public projects</h4>";
    } else {
        echo "<h4 class='span12'>User's public projects</h4>";
        echo $this->Html->css('projects.index', null, array ('inline' => false));
        echo $this->element('Project/list', array('projects' => $projects));
    } ?>
</div>


