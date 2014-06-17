<?php
/**
 *
 * Tempo modal for APP/times/history for the DevTrack system
 * Shows a modal of time vs. tasks
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/SourceKettle/devtrack
 * @package       DevTrack.View.Elements.Time
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

?>
<div class="modal hide" id="<?= $id ?>">
    <table class="modal-body table table-striped table-modal">
        <thead>
            <tr>
                <th><?= __('Description') ?></th>
                <th><?= __('Time spent') ?></th>
            </tr>
        </thead>
        <tbody>
        <?php
            foreach ($times as $time) {
                $txt = ($time['Time']['description']) ? $time['Time']['description'] : 'n/a';
                $url = array('project' => $project['Project']['name'], 'action' => 'view', $time['Time']['id']);

                echo "<tr>";
                echo "<td>".$this->Html->link($txt, $url)."</td>";
                echo "<td>".$time['Time']['minutes']['s']."</td>";
                echo "</tr>";
            }
        ?>
        </tbody>
    </table>
    <div class="modal-footer tempo-footer">
        <?=$this->Bootstrap->button_link(
            $this->DT->t('tempo.modal.add'),
            "#addTimeModal",
            array(
                'data-dismiss' => "modal",
                'data-toggle' => 'modal',
                "class" => "btn-mini",
                "style" => "primary"
            )
        )?>
        <a href="#" class="btn btn-mini" data-dismiss="modal"><?= $this->DT->t('tempo.modal.close') ?></a>
    </div>
</div>
