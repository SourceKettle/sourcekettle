<?php
/**
 *
 * Section element for the DevTrack system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/SourceKettle/devtrack
 * @package       DevTrack.View.Elements.Task.View
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<h3><?= $this->DT->t('description.title') ?></h3>
<div style="word-wrap: break-word;">
    <p><?= $this->DT->parse(h($task['Task']['description'])) ?></p>
</div>
