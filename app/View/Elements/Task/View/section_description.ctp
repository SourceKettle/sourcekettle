<?php
/**
 *
 * Section element for the SourceKettle system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Elements.Task.View
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<h3><?= $this->DT->t('description.title') ?></h3>
<div style="word-wrap: break-word;">
    <p><?= $this->Markitup->parse($task['Task']['description']) ?></p>
</div>
