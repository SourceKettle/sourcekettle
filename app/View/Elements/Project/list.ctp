<?php
/**
 *
 * Renders a well with basic project information
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Elements.Project
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

if (isset($projects)){
    foreach ($projects as $project): ?>
        <? if (!isset($nospan)){?> <div class="span4"><?}?>
            <div class="well project-well">
                <h3 class="project-title"><?=$this->Html->link($project['Project']['name'], array('controller' => 'projects', 'action' => '.', 'project' => $project['Project']['name']), array('class' => 'project-link'))?>
                <span style="float: right;"><?= $this->Bootstrap->icon((($project['Project']['public']) ? 'globe' : 'lock'), 'black') ?></span></h3>

                <? if (!empty($project['Project']['description'])){
                    ?><p class="project-desc"><?= $this->Text->truncate(h($project['Project']['description']), 100)?></p><?
                } else {
                    ?><p class="project-desc"><em>No description available</em></p><?
                }?>
                <p class="project-time">Last Modified: <?=$this->Time->timeAgoInWords($project['Project']['modified'])?></p>
            </div>
        <? if (!isset($nospan)){?> </div><?}?>
    <?php endforeach;
} ?>
