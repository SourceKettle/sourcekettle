<?= $this->Form->create('Settings', array('action'=>$action)) ?>
	<? if(count($items) == 1) {?>
    <div class="input-append">
	<? } else { ?>
	<div class="input">
	<? } ?>

	<? foreach ($items as $name => $value) { ?>
        <?= $this->Form->text($name, array('id' => 'appendedInputButton', 'class' => 'xlarge', "value" => $value)) ?>
	<? } ?>
    <?= $this->Bootstrap->button(__("Change"), array('escape' => false, 'style' => 'primary')) ?>
    </div>
<?= $this->Form->end() ?>
