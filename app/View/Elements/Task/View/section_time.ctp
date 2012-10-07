<?php
/**
 *
 * Section element for the DevTrack system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Elements.Task.View
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$this->Html->scriptBlock("
    $('.timeButton').bind('click', function() {
        $('option[value={$task['Task']['id']}]').attr('selected', 'selected');
    });
", array('inline' => false));

echo $this->element('Time/modal_add');
?>
<h3>
    <?= $this->DT->t('time.title') ?>
    <?= $this->Bootstrap->button_link($this->DT->t('time.button'), '#addTimeModal', array('size' => 'mini', 'data-toggle' => 'modal', 'class' => 'timeButton')) ?>
</h3>
<div>
<?php
    if (empty($times)) {
        echo "<p>No time logged</p>";
    }
    foreach ($times as $time) {
        $i = $this->Bootstrap->label($this->Bootstrap->icon("time", "white"), "info");
        $o = $time['User']['name'];
        $a = $this->Html->link(
            $time['Time']['mins']['s'],
            array(
                'project' => $project['Project']['name'],
                'controller' => 'times',
                'action' => 'view',
                $time['Time']['id']
            )
        );
        echo "<p>{$i} {$a} - {$o}</p>";
    }
?>
</div>