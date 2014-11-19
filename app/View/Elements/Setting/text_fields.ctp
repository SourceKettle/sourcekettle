<?= $this->Form->create('Setting', array('action'=>'set')) ?>
	<? foreach ($items as $item) { ?>
	<tr>
		<th><h4><?=h($item['label'])?> <small><?=h($item['description'])?></small></h4></th>
		<td>
        <?= $this->Form->text($item['name'], array('id' => $item['name'], 'class' => 'xlarge', "value" => $item['value'])) ?>
		</td>
	</tr>
	<? } ?>
	<tr>
	<td>&nbsp;</td>
    <td><?= $this->Bootstrap->button(__("Update"), array('escape' => false, 'style' => 'primary')) ?></td>
	</tr>
<?= $this->Form->end() ?>
