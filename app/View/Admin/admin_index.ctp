<?php
/**
 *
 * View class for APP/admin/index for the SourceKettle system
 * View will render a stats for the system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Admin
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */ ?>

<?=$this->Html->script('/flot/jquery.flot.min', array('inline' => false))?>
<?=$this->Html->script('/flot/jquery.flot.pie.min', array('inline' => false))?>

<div class="row-fluid">
    <div class="span2">
        <?= $this->element('Sidebar/admin') ?>
    </div>
    <div class="span10">
	    <div class="row-fluid">
	        <div class="well span6">
	            <ul id="projectcounts" class="unstyled">
	                <li class="recent-projects" data-numprojects="<?=h($projectsByActivity['recent'])?>" data-projectstatus="<?=h(__('Recently updated'))?>">
	                   <?=h($projectsByActivity['recent']).' '.__("recently updated projects")?>
	                </li>
	
	                <li class="active-projects" data-numprojects="<?=h($projectsByActivity['active'])?>" data-projectstatus="<?=h(__('Active'))?>">
	                   <?=h($projectsByActivity['active']).' '.__("active projects")?>
	                </li>
	
	                <li class="stale-projects" data-numprojects="<?=h($projectsByActivity['stale'])?>" data-projectstatus="<?=h(__('Stale'))?>">
	                   <?=h($projectsByActivity['stale']).' '.__("stale projects")?>
	                </li>
	
	                <li class="dead-projects" data-numprojects="<?=h($projectsByActivity['dead'])?>" data-projectstatus="<?=h(__('Dead'))?>">
	                   <?=h($projectsByActivity['dead']).' '.__("dead projects")?>
	                </li>
	
	                <li class="unused-projects" data-numprojects="<?=h($projectsByActivity['unused'])?>" data-projectstatus="<?=h(__('Unused'))?>">
	                   <?=h($projectsByActivity['unused']).' '.__("unused projects")?>
	                </li>
	
				</ul>
	            </div>

				<div class="well span6">
				<?=h($numUsers).' '.__("users in the system")?>
				</div>
	        </div>
		</div>
    </div>
</div>
