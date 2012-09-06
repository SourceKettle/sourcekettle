<?php
/**
 *
 * View class for APP/milestones/add for the DevTrack system
 * Add a new milestone for a project
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Milestones
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
$this->Html->script('bootstrap-datepicker', array('block' => 'scriptBottom'));
$this->Html->scriptBlock("$('.dp1').datepicker()", array('inline' => false));
$this->Html->css('datepicker', null, array ('inline' => false));
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

                <div class="row-fluid">
                    <div class="span8">
                        <?php
                        echo $this->Form->create('Milestone', array('class' => 'well form-horizontal'));

                        echo $this->Bootstrap->input("subject", array(
                            "input" => $this->Form->text("subject", array("class" => "span12", "placeholder" => $this->DT->t('form.subject.placeholder'))),
                            "label" => $this->DT->t('form.subject.label'),
                        ));

                        echo $this->Bootstrap->input("description", array(
                            "input" => $this->Form->textarea("description", array("placeholder" => $this->DT->t('form.description.placeholder'), 'class' => 'span12')),
                            "label" => $this->DT->t('form.description.label')
                        ));

                        echo "<br>";

                        echo $this->Bootstrap->input("due", array(
                            "input" => $this->Form->text("due", array("class" => "dp1", "value" => date('Y-m-d', time()), "data-date-format" => "yyyy-mm-dd")),
                            "label" => $this->DT->t('form.due.label'),
                            "help_block" => $this->DT->t('form.due.help')
                        ));

                        echo $this->Bootstrap->button($this->DT->t('form.submit'), array("style" => "primary", "size" => "normal", 'class' => 'controls'));

                        echo $this->Form->end();
                        ?>
                    </div>

                    <div class="span4">
                        <h3>What is a 'Milestone'?</h3>
                        <div>
                            <p>At DevTrack, we define a Milestone as a sort of target. It guides us in what work should be done by a certain time. A Milestone can help to prevent you, and your team mates, from straying from the path of progress.</p>
                            <br>
                            <p><strong>Step 1)</strong> Pick a target, or list of features/things you would like done by a certain time.</p>
                            <br>
                            <p><strong>Step 2)</strong> Stick to that target.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

