<?php
/**
 *
 * View class for APP/projects/admin_view for the DevTrack system
 * View will render a project
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/SourceKettle/devtrack
 * @package       DevTrack.View.Projects
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
$details = array(
    '2' => array(
        'icon' => 'wrench',
        'text' => 'Admin',
        'action' => 'admin_makeadmin',
    ),
    '1' => array(
        'icon' => 'user',
        'text' => 'User',
        'action' => 'admin_makeuser',
    ),
    '0' => array(
        'icon' => 'search',
        'text' => 'Guest',
        'action' => 'admin_makeguest',
    ),
);

echo $this->Bootstrap->page_header('Administration <small>what does this bit do</small>'); ?>

<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/admin') ?>
    </div>
    <div class="span10">
        <div class="row-fluid">
            <?php
            echo $this->Form->create('Project', array('class' => 'span7 well form-horizontal', 'action' => 'admin_edit'));

            echo '<h3>Edit project details</h3>';

            echo $this->Bootstrap->input("name", array(
                "input" => $this->Form->text("name", array("class" => "span11")),
            ));

            echo $this->Bootstrap->input("description", array(
                "input" => $this->Form->textarea("description", array("class" => "span11")),
            ));

            echo $this->Bootstrap->input("public", array(
                "input" => $this->Form->checkbox("public"),
            ));

            echo $this->Bootstrap->button("Update", array("style" => "primary", "size" => "large", 'class' => 'controls'));

            echo $this->Form->end();
            ?>
            <div class="span5 well">
                <h3>Additional Information</h3>
                <dl class="dl-horizontal">
                    <dt>Created</dt><dd><?= $this->Time->timeAgoInWords($this->request->data['Project']['created']) ?></dd>

                    <dt>Updated</dt><dd><?= $this->Time->timeAgoInWords($this->request->data['Project']['modified']) ?></dd>

                    <dt>Type</dt><dd><?= h($this->request->data['RepoType']['name']) ?></dd>
                </dl>
            </div>
            <div class="span5 well">
                <h3>Add a user</h3>
                <?= $this->Form->create('Collaborator', array('url' => array('action' => 'admin_add'), 'class' => 'form-inline')) ?>
                <div class="input-append">
                    <?= $this->Form->text("name", array('id' => 'appendedInputButton', "placeholder" => "john.smith@example.com", "data-provide" => "typeahead")) ?>
                    <?= $this->Form->hidden('Project.id') ?>
                    <?= $this->Bootstrap->button("Add", array('escape' => false, 'style' => 'success')) ?>
                </div>
                <?= $this->Form->end() ?>

                <? foreach ( $details as $level => $detail ) : ?>
                <h3>Project <?= h($detail['text']) ?>s</h3>
                <table class="table table-striped">
                    <tbody>
                    <? foreach ( $this->request->data['Collaborator'] as $c ) : ?>
                        <? if ( $c['access_level'] == $level ) : ?>
                        <tr>
                            <td>
                                <?= $this->Html->link($c['User']['name'], array('controllers' => 'users', 'action' => 'admin_view', $c['User']['id'])) ?>
                            </td>
                            <td>
                            <?php
                                echo $this->Bootstrap->button_form(
                                    $this->Bootstrap->icon('eject', 'white'),
                                    $this->Html->url(array('controller' => 'collaborators', 'action' => 'admin_delete', $c['id']), true),
                                    array('escape'=>false, 'style' => 'danger', 'size' => 'mini', 'class' => 'pull-right'),
                                    "Are you sure you want to remove " . h($c['User']['name']) . "?"
                                );
                                echo $this->Bootstrap->button_dropdown($this->Bootstrap->icon($details[$c['access_level']]['icon'], 'white'), array(
                                    "style" => "primary",
                                    "size" => "mini",
                                    'class' => 'pull-right',
                                    "links" => array(
                                        $this->Html->link($this->Bootstrap->icon($details[2]['icon'])." Make an ".$details[2]['text'],
                                                array('controller' => 'collaborators', 'action' => $details[2]['action'], $c['id']),
                                                array('escape' => false)),
                                        $this->Html->link($this->Bootstrap->icon($details[1]['icon'])." Make a " .$details[1]['text'],
                                                array('controller' => 'collaborators', 'action' => $details[1]['action'], $c['id']),
                                                array('escape' => false)),
                                        null,
                                        $this->Html->link($this->Bootstrap->icon($details[0]['icon'])." Make a " .$details[0]['text'],
                                                array('controller' => 'collaborators', 'action' => $details[0]['action'], $c['id']),
                                                array('escape' => false)),
                                    )
                                ));
                            ?>
                            </td>
                        </tr>
                        <? endif; ?>
                    <? endforeach; ?>
                    </tbody>
                </table>
                <? endforeach; ?>
            </div>
        </div>
    </div>
</div>
<style type="text/css">.btn-group {float: right; margin-right: 10px;}</style>
