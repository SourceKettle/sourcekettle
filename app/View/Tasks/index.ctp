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


                    <div class="span4">
                        <div class="well col">
                            <h2><?= $this->DT->t('column.user.title') ?></h2>
                            <hr />
                            <?= $this->element('Task/Board/'.((!empty($user))?'full_column':'empty_user'),
                                array('tasks' => $user, 'e' => $user_empty)
                            ) ?>
                        </div>
                    </div>

                <? if (!empty($team)) : ?>
                    <div class="span4">
                        <div class="well col">
                            <h2><?= $this->DT->t('column.team.title') ?></h2>
                            <hr />
                            <?= $this->element('Task/Board/full_column',
                                array('tasks' => $team, 'e' => $team_empty)
                            ) ?>
                        </div>
                    </div>
                <?  $i = 1;
                else :
                    $i = 2;
                endif; ?>
                    <div class="span<?= $i*4 ?>">
                        <div class="well col">
                            <h2><?= $this->DT->t('column.others.title') ?></h2>
                            <hr />
                            <?= $this->element('Task/Board/'.((!empty($others))?(($i>1) ? 'full_column_2' : 'full_column') : 'empty_others'),
                                array('tasks' => $others, 'e' => $others_empty)
                            ) ?>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
