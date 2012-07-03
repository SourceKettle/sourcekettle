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

$smallText = " <small>" . $project['Project']['description'] . " </small>";
$pname = $project['Project']['name'];
$details = array(
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

echo $this->Bootstrap->page_header($pname . $smallText);?>

<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="span10">
        <div class="row">
            <div class="span6">
                <div class="well">
                    <h3>Users on this project</h3>
                    <table class="table table-striped">
                        <tbody>
                            <?php foreach ($project['Collaborator'] as $c) : ?>
                            <?php $al = $c['access_level']; ?>
                            <tr>
                                <td><?= $this->Html->link($c['User']['name']." &lt;".$c['User']['email']."&gt;", array('controller' => 'users', 'action' => 'view', $c['User']['id']), array('escape' => false)) ?></td>
                                <td>
                                <?php
                                    echo $this->Bootstrap->button_dropdown($this->Bootstrap->icon($details[$al]['icon'], 'white')." ".$details[$al]['text'], array(
                                        "style" => "primary",
                                        "size" => "mini",
                                        "links" => array(
                                            $this->Html->link($this->Bootstrap->icon($details[2]['icon'])." Make an ".$details[2]['text'],
                                                    array('project' => $pname, 'action' => $details[2]['action'], $c['User']['id']),
                                                    array('escape' => false)),
                                            $this->Html->link($this->Bootstrap->icon($details[1]['icon'])." Make a " .$details[1]['text'],
                                                    array('project' => $pname, 'action' => $details[1]['action'], $c['User']['id']),
                                                    array('escape' => false)),
                                            null,
                                            $this->Html->link($this->Bootstrap->icon($details[0]['icon'])." Make a " .$details[0]['text'],
                                                    array('project' => $pname, 'action' => $details[0]['action'], $c['User']['id']),
                                                    array('escape' => false)),
                                        )
                                    ));
                                ?>
                                </td>
                                <td>
                                <?php
                                    echo $this->Bootstrap->button_form(
                                        $this->Bootstrap->icon('eject', 'white')." Remove",
                                        $this->Html->url(array('controller' => 'collaborators', 'project' => $pname, 'action' => 'delete', $c['id']), true),
                                        array('escape'=>false, 'style' => 'danger', 'size' => 'mini'),
                                        "Are you sure you want to delete " . $c['User']['name'] . "?"
                                    );
                                ?>
                                </td>
                            </tr>
                            <? endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="span4">
                <div class="well">
                    <h3>Add a user</h3>
                    <?php echo $this->Form->create('Collaborator', array('url' => array('action' => 'add', 'project' => $project['Project']['name']), 'class' => 'form-inline')); ?>
                    <?php

                    echo $this->Bootstrap->basic_input("name", array(
                        "input" => $this->Form->text("name", array('class' => 'input-large', "placeholder" => "john.smith@example.com", "data-provide" => "typeahead")),
                        "label" => false,
                    ));

                    echo " ".$this->Bootstrap->button($this->Bootstrap->icon('plus', 'white')." Add", array('escape' => false, 'style' => 'success', 'size' => 'mini'));

                    echo $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
