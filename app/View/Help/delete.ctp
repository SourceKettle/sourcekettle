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
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Help
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

echo $this->Bootstrap->page_header('HELP!'); ?>
  
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/help') ?>
    </div>
    <div class="span10">
        <div class="well">
          <h3>Deleting your account</h3>

          <p>
            It's not very likely, but at some point you may wish to completely delete your account.  You will be asked for confirmation first.
          </p>

          <p><strong>Very Important Notice:</strong> be very, <em>very</em> sure before clicking 'yes'! It will also delete any projects for which you are the only contributor, and this is <strong>not reversible!</strong></p>

          <p>
            Note that this option is only available for "internal" accounts - for more information about "internal" and "external" accounts, see the <?= $this->Html->link('account details help page', array('controller' => 'help', 'action' => 'details'))?>.
          </p>
        </div>
    </div>
</div>
