<?php
/**
 *
 * View class for APP/help/user for the DevTrack system
 * View display help for the user section of the site
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Help
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

echo $this->Bootstrap->page_header('HELP!'); ?>
	
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/user') ?>
    </div>
    <div class="span10">
        <div class="well">
            <div class="row">
            </div>
        </div>
    </div>
</div>
