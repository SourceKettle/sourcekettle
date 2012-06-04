<?php
/**
 *
 * View class for APP/projects/view for the DevTrack system
 * View will render a specific project
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
 
$smallText = " <small>" . $project['Project']['description'] . " </small>";
$open = "<i style='margin-top:15px' class=\"icon-" . (($project['Project']['public']) ? 'globe' : 'lock') . "\"></i>";

echo $this->Bootstrap->page_header($project['Project']['name'] . $smallText . $open); ?>
	
<div class="row">
    <div class="span2">
        <?= $this->element('project_sidebar', array('id' => $project['Project']['id'], 'action' => 'view')) ?>
    </div>
    <div class="span9">
    <h2><?php  echo __('Project');?></h2>
    	<dl>
    		<dt><?php echo __('Public'); ?></dt>
    		<dd>
    			<?php echo h($project['Project']['public']); ?>
    			&nbsp;
    		</dd>
    		<dt><?php echo __('Repo Type'); ?></dt>
    		<dd>
    			<?php echo $this->Html->link($project['RepoType']['name'], array('controller' => 'repo_types', 'action' => 'view', $project['RepoType']['id'])); ?>
    			&nbsp;
    		</dd>
    		<dt><?php echo __('Wiki Enabled'); ?></dt>
    		<dd>
    			<?php echo h($project['Project']['wiki_enabled']); ?>
    			&nbsp;
    		</dd>
    		<dt><?php echo __('Task Tracking Enabled'); ?></dt>
    		<dd>
    			<?php echo h($project['Project']['task_tracking_enabled']); ?>
    			&nbsp;
    		</dd>
    		<dt><?php echo __('Time Management Enabled'); ?></dt>
    		<dd>
    			<?php echo h($project['Project']['time_management_enabled']); ?>
    			&nbsp;
    		</dd>
    		<dt><?php echo __('Created'); ?></dt>
    		<dd>
    			<?php echo h($project['Project']['created']); ?>
    			&nbsp;
    		</dd>
    		<dt><?php echo __('Modified'); ?></dt>
    		<dd>
    			<?php echo h($project['Project']['modified']); ?>
    			&nbsp;
    		</dd>
    	</dl>
    </div>

</div>