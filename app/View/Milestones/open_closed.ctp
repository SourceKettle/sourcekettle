<?php
/**
 *
 * View class for APP/milestones/open|closed for the DevTrack system
 * Shows a list of milestones for a project
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/SourceKettle/devtrack
 * @package       DevTrack.View.Milestones
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$this->Html->css('milestones.index', null, array ('inline' => false));

?>

<?= $this->DT->pHeader() ?>
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="span10">
        <div class="row">
            <?= $this->element('Milestone/topbar_index') ?>
            <div class="span10">
                <?php
                if (empty($milestones)) {
                    echo '<div class="span10" style="text-align:center"><h1>No '.$this->DT->t('header.text').'</h1></div>';
                } else {
                    foreach ($milestones as $milestone) {
                        echo $this->element('Milestone/block', array('milestone' => $milestone));
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>
