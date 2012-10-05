<?php
/**
 *
 * View class for APP/help/create for the DevTrack system
 * Display the help page for creating new projects
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
          <h3>Themes</h3>

          <p>
            Thanks to the awesome people at <?= $this->Html->link('BootSwatch', 'http://bootswatch.com')?>, you have access to a variety of interesting themes to make DevTrack look Amazing and Wonderful<sup>TM</sup>!
          </p>

          <p>
            Simply select a theme from the list and click 'Update' to change your theme.
          </p>

        </div>
    </div>
</div>
