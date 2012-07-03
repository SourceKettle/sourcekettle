<?php
/**
 *
 * View class for APP/projects/admin_add for the DevTrack system
 * View allow admin to create a new project
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Projects
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

echo $this->Bootstrap->page_header('Administration <small>add another nugget of knowledge</small>'); ?>

<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/admin') ?>
    </div>
    <div class="span10">
        <div class="row-fluid">
            <?php
            echo $this->Form->create('Project', array('class' => 'span7 well form-horizontal', 'action' => 'admin_add'));

            echo '<h3>New projects details</h3>';

            echo $this->Bootstrap->input("name", array(
                "input" => $this->Form->text("name", array("class" => "span11")),
            ));

            echo $this->Bootstrap->input("description", array(
                "input" => $this->Form->textarea("description", array("class" => "span11")),
            ));

            echo $this->Bootstrap->input("public", array(
                "input" => $this->Form->checkbox("public"),
            ));

            echo $this->Bootstrap->input("repositoryType", array(
                "input" => $this->Form->select('repo_type', $repoTypes, array('empty'=>false)),
            ));

            echo $this->Bootstrap->button("Create", array("style" => "primary", "size" => "large", 'class' => 'controls'));

            echo $this->Form->end();
            ?>
        </div>
    </div>
</div>
