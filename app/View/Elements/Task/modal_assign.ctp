<?php
/**
 *
 * Modal class for APP/tasks/add for the DevTrack system
 * Shows a modal box for assigning users
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Elements.Task
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
$this->Html->css('autocomplete', null, array('inline' => false));
$this->Html->script('jquery.autocomplete', array('block' => 'scriptBottom'));
$this->Html->scriptBlock("
        jQuery(function(){
            var options, a;
            var items = [];
            $.getJSON('".$this->Html->url('/api/projects/view/'.$project['Project']['id'])."', function(data) {
                $.each(data['collaborators'], function(key, val) {
                    $.getJSON('".$this->Html->url('/api/users/view/')."'+val, function(data) {
                        items.push(data['name']+' ('+data['email']+')');
                        items.push(data['email']+' ('+data['name']+')');
                    });
                });
            });
            options = {
                'delimiter': '/(,|;)\s*/',
                'lookup': items
            };
            a = $('.userinput').autocomplete(options);
        });
    ", array('inline' => false));

?>
<div class="modal hide" id="assignModal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">x</button>
        <h4><?= $this->DT->t('modal.assign.header') ?></h4>
    </div>
    <?= $this->Form->create('TaskAssignee') ?>
    <div class="modal-body">
        <p>
            <?= $this->DT->t('modal.assign.body') ?>
            '<?= $this->DT->t('modal.assign.submit') ?>'
        </p>
        <?php
        echo $this->Bootstrap->input("assignee", array(
            "input" => $this->Form->text("assignee", array("class" => "input-xlarge userinput", "placeholder" => $this->DT->t('modal.assign.assignee.placeholder'))),
            "label" => $this->DT->t('modal.assign.assignee.label'),
        ));
        ?>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal"><?= $this->DT->t('modal.assign.close') ?></a>
        <?= $this->Bootstrap->button($this->DT->t('modal.assign.submit'), array("style" => "primary")) ?>
    </div>
    <?= $this->Form->end() ?>
</div>
