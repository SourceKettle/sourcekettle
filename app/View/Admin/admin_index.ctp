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
 */

echo $this->Bootstrap->page_header('Administration <small>system overview</small>'); ?>
	
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/admin') ?>
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
