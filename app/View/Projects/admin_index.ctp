<?php
/**
 *
 * View class for APP/projects/admin_index for the DevTrack system
 * View will render lists of projects
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

echo $this->Bootstrap->page_header('Administration <small>da vinci code locator</small>'); ?>

<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/admin') ?>
    </div>
    <div class="span10">
       <div class="row-fluid">
            <?php
                echo $this->Form->create('Project',
                    array(
                        'class' => 'form-inline input-append'
                    )
                );

                echo $this->element('components/user_typeahead_input',
                    array(
                        'name' => 'name',
                        'properties' => array(
                            'id' => 'appendedInputButton',
                            'class' => 'span11',
                            "placeholder" => "Start typing a project name...",
                            'label' => false
                        ),
                        'url' => array(
                            'controller' => 'projects',
                            'action' => 'autocomplete',
                            'api' => true
                        )
                    )
                );
                echo $this->Bootstrap->button('Search', array('escape' => false, 'style' => 'primary'));

                echo $this->Form->end();
            ?>
            <table class="well table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th width="15%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <? $this->Html->script('projects.overview.js', array('inline' => false)) ?>
                <? foreach ( $projects as $project ) : ?>
                    <tr>
                        <td>
                            <?= $this->Html->link($project['Project']['name'], array('action' => 'view', $project['Project']['id']))?>
                        </td>
                        <td>
                            <div id='project_description'>

                                <? $more_link = '... <span id="view_more_button">' .$this->Html->link('Read More', '#') . '</span>'; ?>

                                <?= $this->Text->truncate($project['Project']['description'], 250, array('ending' => $more_link, 'exact' => false, 'html' => false)) ?>
                                <div id='full_description' style='display: none'>
                                    <?= $project['Project']['description'] ?>
                                </div>
                            </div>
                        </td>
                        <td>
                        <?php
                            echo $this->Bootstrap->button_form(
                                $this->Bootstrap->icon('eject', 'white'),
                                $this->Html->url(array('controller' => 'projects', 'action' => 'admin_delete', $project['Project']['id']), true),
                                array('escape'=>false, 'style' => 'danger', 'size' => 'mini', 'class' => ''),
                                "Are you sure you want to delete " . $project['Project']['name'] . "?"
                            );
                        ?>
                        </td>
                    </tr>
                <? endforeach; ?>
                </tbody>
            </table>
            <?= $this->element('pagination') ?>
        </div>
    </div>
</div>
