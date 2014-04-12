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
 * @link          http://github.com/SourceKettle/devtrack
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
                            "placeholder" => __("Start typing a project name..."),
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
                            <?= $this->Html->link($project['Project']['name'], array('action' => 'view', $project['Project']['id'], 'admin' => false))?>
                        </td>
                        <td>
                            <div id='project_description'>

                                <? $more_link = '... <span id="view_more_button">' .$this->Html->link(__('Read More'), '#') . '</span>'; ?>

                                <?= $this->Text->truncate(h($project['Project']['description']), 250, array('ending' => $more_link, 'exact' => false, 'html' => false)) ?>
                                <div id='full_description' style='display: none'>
                                    <?= h($project['Project']['description']) ?>
                                </div>
                            </div>
                        </td>
                        <td>
                        <?= $this->Bootstrap->button(
							$this->Html->link(
								$this->Bootstrap->icon('cog'),
								array(
										'project' => $project['Project']['name'],
										'action' => 'edit',
										'controller' => 'projects',
										'admin' => false
									 ),
								array(
									'escape' => false
								 )
							),
							array(
								'size' => 'mini',
								'title' => __('Edit project settings'),
							)
						)?>
						&nbsp;
						<?= $this->Bootstrap->button(
							$this->Html->link(
								$this->Bootstrap->icon('user'),
								array(
										'project' => $project['Project']['name'],
										'action' => 'collaborators',
										'controller' => 'projects',
										'admin' => false
									 ),
								array(
									'escape' => false
								 )
							),
							array(
								'size' => 'mini',
								'title' => __('Edit project collaborators'),
							)
						)?>
						&nbsp;
                        <?= $this->Bootstrap->button_form(
                            $this->Bootstrap->icon('eject', 'white'),
                            $this->Html->url(array('controller' => 'projects', 'action' => 'admin_delete', $project['Project']['id']), true),
                            array('escape'=>false, 'style' => 'danger', 'size' => 'mini', 'class' => ''),
                            __("Are you sure you want to delete ") . h($project['Project']['name']) . "?"
                        )?>
                        
                        </td>
                    </tr>
                <? endforeach; ?>
                </tbody>
            </table>
            <?= $this->element('pagination') ?>
        </div>
    </div>
</div>
