<?php
/**
 *
 * View class for APP/help/dashboard for the DevTrack system
 * Display the help page for the dashboard part of the application
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
        <?= $this->element('Sidebar/help') ?>
    </div>
    <div class="span10">
        <div class="well">
          <h3>Dashboard</h3>
          <p>
            The Dashboard display shows current information relating to everything you are working on:

            <ul>
              <li><strong>Recent events:</strong> What's been happening on projects you're working on ("What's everybody up to?")</li>
              <li><strong>Assigned tasks:</strong> Tasks that are assigned to you ("what am I supposed to be doing?")</li>
              <li><strong>Most recent projects:</strong> A list of your most recently created projects</li>
            </ul>
          </p>

        </div>
    </div>
</div>
