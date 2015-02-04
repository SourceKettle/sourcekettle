<?php
/**
 *
 * View class for APP/projects/admin_index for the SourceKettle system
 * View will render lists of projects
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Projects
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>

<div class="row-fluid">
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

                echo $this->element('typeahead_input',
                    array(
                        'name' => 'name',
						'jsonListName' => 'projects',
                        'url' => array(
                            'api' => true,
                            'controller' => 'projects',
                            'action' => 'autocomplete',
                        ),
                        'properties' => array(
                            'id' => 'projectSearchBox',
                            'class' => 'span11',
                            "placeholder" => __("Start typing a project name..."),
                            'label' => false,
                        ),
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
                            <?= $this->Html->link($project['Project']['name'], array('controller' => 'projects', 'action' => 'view', 'project' => $project['Project']['name'], 'admin' => false))?>
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
						<div class="btn-group">
                        <?= $this->Bootstrap->button_link(
							$this->Bootstrap->icon('cog'),
							$this->Html->url(
								array(
									'project' => $project['Project']['name'],
									'action' => 'edit',
									'controller' => 'projects',
									'admin' => false
								 )
							),
							array(
								'size' => 'mini',
								'title' => __('Edit project settings'),
								'escape' => false
							)
						)?>
						<?= $this->Bootstrap->button_link(
							$this->Bootstrap->icon('user'),
							$this->Html->url(
								array(
									'project' => $project['Project']['name'],
									'action' => 'collaborators',
									'controller' => 'projects',
									'admin' => false
								 )
							),
							array(
								'size' => 'mini',
								'title' => __('Edit project collaborators'),
								'escape' => false
							)
						)?>
						<?= $this->Bootstrap->button_link(
							$this->Bootstrap->icon('random'),
							$this->Html->url(
								array(
									'project' => $project['Project']['name'],
									'action' => 'rename',
									'controller' => 'projects',
									'admin' => true
								 )
							),
							array(
								'size' => 'mini',
								'title' => __('Rename project'),
								'escape' => false
							)
						)?>
                        <?= $this->Bootstrap->button_form(
                            $this->Bootstrap->icon('eject', 'white'),
                            $this->Html->url(array('controller' => 'projects', 'action' => 'delete', 'admin' => false, 'project' => $project['Project']['name']), true),
                            array('escape'=>false, 'style' => 'danger', 'size' => 'mini', 'class' => ''),
                            __("Are you sure you want to delete ") . h($project['Project']['name']) . "?"
                        )?>
						</div>
                        
                        </td>
                    </tr>
                <? endforeach; ?>
                </tbody>
            </table>
            <?= $this->element('pagination') ?>
        </div>
    </div>
</div>
