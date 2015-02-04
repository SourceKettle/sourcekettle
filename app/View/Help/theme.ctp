<?php
/**
 *
 * View class for APP/help/create for the SourceKettle system
 * Display the help page for creating new projects
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          https://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Help
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
  
<div class="row-fluid">
    <div class="span2">
        <?= $this->element('Sidebar/help') ?>
    </div>
    <div class="span10">
        <div class="well">
          <h3>Themes</h3>

          <p>
            Thanks to the awesome people at <?= $this->Html->link('BootSwatch', 'http://bootswatch.com')?>, you have access to a variety of interesting themes to make SourceKettle look Amazing and Wonderful<sup>TM</sup>!
          </p>

          <p>
            Simply select a theme from the list and click 'Update' to change your theme.
          </p>

        </div>
    </div>
</div>
