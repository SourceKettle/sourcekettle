<?php
/**
 *
 * View class for APP/help/index for the DevTrack system
 * Display the landing page for the help part of the application
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
        <?= $this->element('Sidebar/help') ?>
    </div>
    <div class="span10">
        <div class="well">
          <h3>Got a problem?</h3>

          <h5>You're in the right place!</h5>

          <p>Click on the navigation links on the left to find the relevant section.</p> 

          <p>Still can't find what you're looking for? Ask us on Github <?= $this->Html->link("https://github.com/chrisbulmer/devtrack/issues", "https://github.com/chrisbulmer/devtrack/issues") ?></p>
        </div>
    </div>
</div>
