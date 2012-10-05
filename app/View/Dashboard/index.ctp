<?= $this->element('beta_warning') ?>
<?= $this->Bootstrap->page_header("Dashboard <small>welcome " . strtolower($user_name) . "</small>") ?>

<div class="row">
    <div class="span8">
    </div>

    <div class="span4">
        <h3>Your recent projects</h3>

        <?= $this->Element("Project/list", array('projects' => $projects, 'nospan' => true)) ?>  
    </div>
</div>