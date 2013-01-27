<?php
/**
 *
 * Element for APP/attachments/[index|video|image|other] for the DevTrack system
 * Renders an empty display when no files are present
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/SourceKettle/devtrack
 * @package       DevTrack.View.Elements.Attachment
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$this->Html->css('pages/attachments', null, array ('inline' => false));
?>
<div class="emptyFiles">
    <h4><?= $this->DT->t('empty.text', array('action'=>'element.empty')) ?></h4>
</div>
