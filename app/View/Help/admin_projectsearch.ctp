<?php
/**
 *
 * View class for APP/help/admin_index for the DevTrack system
 * View display help for the admin section of the site
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/SourceKettle/devtrack
 * @package       DevTrack.View.Help
 * @since         DevTrack v 0.1
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
			<div class="span10">
				The project search page gives a paginated list of all the projects in the system.
				Clicking on the project link will take you to the 'edit details' page.
				Clicking on the delete button will delete the project.
				The search box is currently very limited; you need to type in exact project names :-( This is being worked on.
			</div>
            </div>
        </div>
    </div>
</div>
