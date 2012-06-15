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

echo $this->Bootstrap->page_header('Administration <small>system projects</small>'); ?>
	
<div class="row">
    <div class="span2">
        <?= $this->element('admin_sidebar', array('action' => 'projects')) ?>
    </div>
    <div class="span9">
        <div class="row">
            <div class="well span9">
                <div class="row">
                </div>
            </div>
        </div>
    </div>
</div>
