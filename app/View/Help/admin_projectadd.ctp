<?php
/**
 *
 * View class for APP/help/admin_index for the SourceKettle system
 * View display help for the admin section of the site
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Help
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

echo $this->Bootstrap->page_header('HELP!'); ?>
	
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/admin_help') ?>
    </div>
    <div class="span10">
        <div class="well">
            <div class="row">
            </div>
        </div>
    </div>
</div>
