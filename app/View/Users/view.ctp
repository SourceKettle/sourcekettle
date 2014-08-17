<?= $this->Bootstrap->page_header(h($user['User']['name'])); ?>

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
            <?= h($user['User']['email']) ?>
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
    echo $this->Html->css('projects.index', null, array ('inline' => false));
    if (!empty($shared_projects)) {
        echo '<hr>';
        echo '<div class="row">';
        echo "<h4 class='span12'>Projects shared with this user</h4>";
    	foreach ($shared_projects as $project){
			echo $this->Element('Project/block', array('project' => $project));
		}
        echo '</div>';
    }
?>
<hr>
<div class="row">
    <?php
    // Loop through all the projects that a user has access to
    if (empty($projects)) {
        echo "<h4 class='span12'>This user has no public projects</h4>";
    } else {
        echo "<h4 class='span12'>Users public projects</h4>";
    	foreach ($projects as $project){
			echo $this->Element('Project/block', array('project' => $project));
		}

    } ?>
</div>


