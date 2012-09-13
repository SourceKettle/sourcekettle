<?php
/**
 *
 * View class for APP/collaborators/index for the DevTrack system
 * Allows modification of collaborators
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Collaborators
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$_project_name = $project['Project']['name'];
$_levels = array(
    '0' => array(
        'icon' => 'search',
        'text' => 'Guest',
        'action' => 'makeguest',
    ),
    '1' => array(
        'icon' => 'user',
        'text' => 'User',
        'action' => 'makeuser',
    ),
    '2' => array(
        'icon' => 'wrench',
        'text' => 'Admin',
        'action' => 'makeadmin',
    ),
);
?>

<?= $this->DT->pHeader() ?>
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="span10">
        <div class="row">
            <div class="span6">
                <div class="well">
                    <h3><?= $this->DT->t('users.header') ?></h3>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th width="60%"><?= $this->DT->t('users.users') ?></th>
                                <th><?= $this->DT->t('users.role') ?></th>
                                <th width="25%"><?= $this->DT->t('users.actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach (array_reverse($_levels, true) as $_access_level => $_details) :

                            $_access_text   = $_details['text'];
                            $_access_icon = $this->Bootstrap->icon($_details['icon']);

                            $_up_icon   = $this->Bootstrap->icon('arrow-up');
                            $_down_icon = $this->Bootstrap->icon('arrow-down');

                            foreach ($collaborators[$_access_level] as $collaborator) :

                                $_user_name = $collaborator['User']['name'];
                                $_user_mail = $collaborator['User']['email'];
                                $_user_id   = $collaborator['User']['id'];
                                $_user_url  = array('controller' => 'users', 'action' => 'view', $_user_id);

                                $_promote_url = ($_access_level < 2) ? $this->Html->url(array('project' => $_project_name, 'action' => $_levels[$_access_level + 1]['action'], $_user_id), true) : null;
                                $_demote_url  = ($_access_level > 0) ? $this->Html->url(array('project' => $_project_name, 'action' => $_levels[$_access_level - 1]['action'], $_user_id), true) : null;
                                $_delete_url  = $this->Html->url(array('controller' => 'collaborators', 'project' => $_project_name, 'action' => 'delete', $collaborator['Collaborator']['id']), true);

                                $_blank_button = $this->Bootstrap->button($this->Bootstrap->icon('none'), array('escape'=>false, 'size' => 'mini', 'class' => 'disabled'))
                            ?>
                            <tr>
                                <td><?= $this->Html->link("$_user_name &lt;$_user_mail&gt;", $_user_url, array('escape' => false)) ?></td>
                                <td><?= "$_access_icon $_access_text" ?></td>
                                <td>
                                    <? if ($_promote_url) echo $this->Bootstrap->button_form($_up_icon, $_promote_url, array('escape'=>false, 'size' => 'mini')); else echo $_blank_button; ?>
                                    <? if ($_demote_url) echo $this->Bootstrap->button_form($_down_icon, $_demote_url, array('escape'=>false, 'size' => 'mini')); else echo $_blank_button; ?>
                                <?php
                                    echo $this->Bootstrap->button_form(
                                        $this->Bootstrap->icon('eject', 'white'),
                                        $_delete_url,
                                        array('escape'=>false, 'style' => 'danger', 'size' => 'mini'),
                                        str_replace('{user}', $_user_name, $this->DT->t('users.actions.delete'))
                                    );
                                ?>
                                </td>
                            </tr>
                            <?php
                            endforeach;
                        endforeach;
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="span4">
                <div class="well">
                    <h3><?= $this->DT->t('add.header') ?></h3>
                    <?= $this->Form->create('Collaborator', array('url' => array('action' => 'add', 'project' => $project['Project']['name']), 'class' => 'form-inline')) ?>
                    <div class="input-append">
                        <?= $this->Form->text("name", array('id' => 'appendedInputButton', 'class' => 'span3', "placeholder" => "john.smith@example.com", "data-provide" => "typeahead")) ?>
                        <?= $this->Bootstrap->button("Add", array('escape' => false, 'style' => 'success')) ?>
                    </div>
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>
    </div>
</div>
