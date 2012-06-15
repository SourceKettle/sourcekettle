<?php
/**
 *
 * View class for APP/users/admin_index for the DevTrack system
 * View will render lists of users
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

echo $this->Bootstrap->page_header('Administration <small>system users</small>'); ?>
	
<div class="row">
    <div class="span2">
        <?= $this->element('admin_sidebar', array('action' => 'users')) ?>
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
