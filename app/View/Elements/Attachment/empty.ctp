<?php
/**
 *
 * Element for APP/attachments/[index|video|image|other] for the SourceKettle system
 * Renders an empty display when no files are present
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Elements.Attachment
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$this->Html->css('pages/attachments', null, array ('inline' => false));
?>
<div class="emptyFiles">
    <h4><?= $this->DT->t('empty.text', array('action'=>'element.empty')) ?></h4>
</div>
