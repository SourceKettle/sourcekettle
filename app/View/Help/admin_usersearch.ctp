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
			<div class="span10">
			  <p>
				The user search page gives a paginated list of all the users in the system.
			  </p>
			  <ul>
				<li>Clicking on the user link will take you to the 'edit account' page</li>
				<li>Clicking on the delete button will delete the user</li>
				<li>The search box is currently very limited; you need to type in exact email addresses :-( This is being worked on!</li>
			  </ul>
			</div>
            </div>
        </div>
    </div>
</div>
