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
$closed = ($task['Task']['task_status_id'] == 4);

if (!$closed){
    $this->Html->scriptBlock("
        $('.timeButton').bind('click', function() {
            $('option[value={$task['Task']['id']}]').attr('selected', 'selected');
        });
    ", array('inline' => false));

    echo $this->element('Time/modal_add');
}
?>
<h3>
    <?= $this->DT->t('time.title') ?>
    <? if (!$closed){
    echo $this->Bootstrap->button_link($this->DT->t('time.button'), '#addTimeModal', array('size' => 'mini', 'data-toggle' => 'modal', 'class' => 'timeButton'));
    } else {
        echo $this->Bootstrap->button_link($this->DT->t('time.button'), '#addTimeModal', array('size' => 'mini', 'data-toggle' => 'modal', 'class' => 'timeButton', 'disabled'));
    }?>
</h3>
<div>
<?php
    if (empty($times)) {
        echo "<p>No time logged</p>";
    }
    foreach ($times as $time) {
        $i = $this->Bootstrap->label($this->Bootstrap->icon("time", "white"), "info");
        $o = h($time['User']['name']);
        $a = $this->Html->link(
            $time['Time']['minutes']['s'],
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
