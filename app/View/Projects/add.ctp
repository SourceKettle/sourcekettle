<h1>Add a Project</h1>
<div class="row">
    <div class="span6">
        <?php echo $this->Form->create('Project', array('class' => 'well form-horizontal')); ?>
        <?php 

        echo $this->Bootstrap->input("name", array(
            "input" => $this->Form->text("name"),
            "help_block" => "The 'short' name your project will be known by"
        ));

        echo $this->Bootstrap->input("description", array(
            "input" => $this->Form->textarea("description"),
            "help_block" => "The 'long' waffle explaining your projects intent"
        ));

        echo $this->Bootstrap->input("public", array(
            "input" => $this->Form->checkbox("public"),
        ));

        echo $this->Bootstrap->input("repositoryType", array(
            "input" => $this->Form->select('repo_type', $repoTypes, array('empty'=>false)),
        ));
        ?>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label" for="featuresCheckboxList">Features</label>
            <div class="controls">
                <label class="checkbox">
		            <?=$this->Form->checkbox('wiki_enabled')?> Wiki?
                </label>
                <label class="checkbox">
		            <?=$this->Form->checkbox('task_tracking_enabled')?> Task Tracking?
                </label>
                <label class="checkbox">
		            <?=$this->Form->checkbox('time_management_enabled')?> Time Management?
                </label>
                <p class="help-block"><strong>Note:</strong> Features can be enabled and disabled in the project page later.</p>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="span6"> 
        <?php echo $this->Bootstrap->button("Create Project", array("style" => "primary", "size" => "large", 'class' => 'controls')); ?>
    </div>
</div>
<?php echo $this->Form->end(); ?>
