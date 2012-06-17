<?php
/**
 *
 * View class for APP/users/admin_view for the DevTrack system
 * View will render a user
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Users
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

echo $this->Bootstrap->page_header('Administration <small>what does this bit do</small>'); ?>

<div class="row">
    <div class="span2">
        <?= $this->element('admin_sidebar', array('action' => 'users')) ?>
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

                    <dt>Type</dt><dd><?= $this->request->data['RepoType']['name'] ?></dd>

                </dl>
                <h3>Project Admins</h3>
                <table class="table table-striped">
                    <tbody>
                    <? foreach ( $this->request->data['Collaborator'] as $c ) : ?>
                        <? if ( $c['access_level'] == 2 ) : ?>
                        <tr>
                            <td>
                                <?= $this->Html->link($c['User']['name'], array('controllers' => 'users', 'action' => 'admin_view', $c['User']['id'])) ?>
                            </td>
                            <td>
                            <?php
                                echo $this->Bootstrap->button_form(
                                    $this->Bootstrap->icon('eject', 'white'),
                                    $this->Html->url(array('controller' => 'collaborator', 'action' => 'admin_delete', $c['id']), true),
                                    array('escape'=>false, 'style' => 'danger', 'size' => 'mini', 'class' => 'pull-right'),
                                    "Are you sure you want to remove " . $c['User']['name'] . "?"
                                );
                            ?>
                            </td>
                        </tr>
                        <? endif; ?>
                    <? endforeach; ?>
                    </tbody>
                </table>
                <h3>Project Members</h3>
                    <table class="table table-striped">
                    <tbody>
                    <? foreach ( $this->request->data['Collaborator'] as $c ) : ?>
                        <? if ( $c['access_level'] < 2 ) : ?>
                        <tr>
                            <td>
                                <?= $this->Html->link($c['User']['name'], array('controllers' => 'users', 'action' => 'admin_view', $c['User']['id'])) ?>
                            </td>
                            <td>
                            <?php
                                echo $this->Bootstrap->button_form(
                                    $this->Bootstrap->icon('eject', 'white'),
                                    $this->Html->url(array('controller' => 'collaborator', 'action' => 'admin_delete', $c['id']), true),
                                    array('escape'=>false, 'style' => 'danger', 'size' => 'mini', 'class' => 'pull-right'),
                                    "Are you sure you want to remove " . $c['User']['name'] . "?"
                                );
                            ?>
                            </td>
                        </tr>
                        <? endif; ?>
                    <? endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
