<?php
/**
 *
 * View class for APP/tasks/index for the DevTrack system
 * Shows a list of tasks for a project
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Tasks
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$this->Html->css('tasks.index', null, array ('inline' => false));

?>

<?= $this->DT->pHeader() ?>
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="span10">
        <div class="row">
            <?= $this->element('Topbar/task') ?>
            <div class="span10">
                <div class="row-fluid">


                    <div class="span7">
                        <div class="well col">
                            <h2><?= $this->DT->t('column.tasks.title') ?></h2>
                            <hr />
                            <?= $this->element('Task/Board/'.((!empty($user))?'full_column':'empty'),
                                array('tasks' => $user, 'e' => $user_empty, 'width' => 2, 'c' => 'tasks')
                            ) ?>
                        </div>
                    </div>

                    <div class="span5">
                        <div class="well col">
                        </div>
                    </div>

                    <div class="span5 ">
                        <div class="well col">
                            <h2><?= $this->DT->t('column.history.title') ?></h2>
                            <hr />
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
