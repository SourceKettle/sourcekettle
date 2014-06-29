<?php
/**
 *
 * View class for APP/collaborators/all for the SourceKettle system
 * Show the collaborators on a project
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Collaborators
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$_project_name = $project['Project']['name'];


echo $this->DT->pHeader() ?>
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="span10">
        <div class='row'>
            <? foreach ($collaborators as $collaborator){
                echo $this->element('Collaborator/block', array('collaborator' => $collaborator));
            }
            ?>
        </div>
    </div>
</div>
