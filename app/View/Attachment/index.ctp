<?php
/**
 *
 * View class for APP/attachments/index for the SourceKettle system
 * View will allow user to view uploaded attachments
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Attachment
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

?>

<?= $this->DT->pHeader(__("Files uploaded to the project")) ?>
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="span10">
        <div class="row">
            <?= $this->element('Attachment/topbar') ?>
            <div class="span10">

            <?php if (!empty($attachments)) {
                      echo $this->element('Attachment/full');
                  } else {
                      echo $this->element('Attachment/empty');
                  }
            ?>

            </div>
        </div>
    </div>
</div>
