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
        <?= $this->element('project_sidebar', array('project' => $project['Project']['name'], 'action' => 'view')) ?>
    </div>
    <div class="span9">
        <div class="row">
            <div class="well span9">
                <div class="row">
                    <div class="span3">
                        <h3 style="margin: 0px;">Tasks</h3>
                        <hr style="margin: 0px 0px 5px;">
                        <div class="row-fluid">
                            <div class="span6">
                                <ul class="unstyled">
                                    <li><?= $this->Html->link('0 - Open Tasks', '#', array('style'=>"color: #3266cc;")) ?></li>
                                    <li><?= $this->Html->link('0 - Closed Tasks', '#', array('style'=>"color: #c5d8ff;")) ?></li>
                                    <li><?= $this->Html->link('0 - Total Tasks', '#', array('style'=>"color: #000000;")) ?></li>
                                    <li>0% complete</li>
                                </ul>
                            </div>
                            <div class="span6">
                                <? echo $this->GoogleChart->create()->setType('pie')->setSize(100, 100)->addData(array(20, 0, 50)); ?>
                            </div>
                        </div>
                    </div>
                    <div class="span3">
                        <h3 style="margin: 0px;">Next Milestone</h3>
                        <hr style="margin: 0px 0px 5px;">
                        <ul class="unstyled">
                            <li><strong>Oh Damn</strong></li>
                            <br>
                            <li>Due: 22/10/2012</li>
                            <li>0% complete</li>
                        </ul>
                        <div class="progress progress-striped">
                            <div class="bar" style="width: 40%;"></div>
                        </div>
                    </div>
                    <div class="span3">
                        <h3 style="margin: 0px;">Quick Stats</h3>
                        <hr style="margin: 0px 0px 5px;">
                        <ul class="unstyled">
                            <li><strong>0 Bytes</strong> disk space used in files, notebook &amp; repositories.</li>
                            <li><strong>1 user</strong> from 1 company are working on this project.</li>
                            <li>Last activity was <strong>29 days ago</strong>.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
