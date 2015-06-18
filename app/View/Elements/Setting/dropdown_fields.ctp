<? $addLock = (isset($addLock)? $addLock: false);?>

<? $model = (isset($model)? $model : 'Setting');?>

<?= $this->Form->create($model, array('url' => $url)) ?>
	<? foreach ($items as $item) { ?>
	<tr>
		<th><h4><?=h($item['label'])?> <small><?=h($item['description'])?></small></h4></th>
		<? if ($item['readOnly']) { ?>
        		<?=h($item['value']) ?> <?=$this->Bootstrap->icon('lock')?>
		<? } else { ?>
			<td>
        		<?= $this->Form->input($model.'.'.$item['name'], array('id' => $model.'.'.$item['name'], 'class' => 'xlarge', 'label' => false, "selected" => $item['value'], "options" => $item['options'])) ?>
			</td>
		<? } ?>
		<? if ( $addLock) { ?>
                <td>
                    <?= $this->element('Setting/switch', array('lock' => true, 'id' => '', 'name' => $model.'.'.$item['name'], 'url' => $this->Html->url(array('controller' => 'settings', 'action' => 'set', 'admin' => 'true', 'lock')), 'sectionHide' => '', 'value' => $item['locked'])) ?>
                </td>
		<? } ?>
	</tr>
	<? } ?>
	<tr>
	<td>&nbsp;</td>
    <td><?= $this->Bootstrap->button(__("Update"), array('escape' => false, 'style' => 'primary')) ?></td>
	</tr>
<?= $this->Form->end() ?>
