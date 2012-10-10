<?= $this->Bootstrap->page_header($user['User']['name']); ?>

<div class="row">
    <div class="span3">
        <?= $this->Gravatar->image(
            $user['User']['email'],
            array('size' => 200),
            array('alt' => $user['User']['name'])
        ) ?>
    </div>
    <dl class="dl-horizontal span9">
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
<?php
    if (!empty($shared_projects)) {
        echo '<hr>';
        echo '<div class="row">';
        echo "<h3 class='span12'>Projects shared with this user</h3>";
        echo $this->Element("Project/list", array('projects' => $shared_projects));
        echo '</div>';
    }
?>
<hr>
<div class="row">
    <?php
    if (!empty($shared_projects)) echo "<h3 class='span12'>Users public projects</h3>";

    // Loop through all the projects that a user has access to
    if (empty($projects)) {
        echo "<p class='span12'>This user has no public projects</p>";
    } else {
        echo $this->Element("Project/list", array('projects' => $projects));
    } ?>
</div>


