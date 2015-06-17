<?php

$this->Html->css('/bootstrap-switch/bootstrap-switch.min', array ('inline' => false));
$this->Html->script('/bootstrap-switch/bootstrap-switch.min', array ('inline' => false, 'once' => true));
$this->Html->script('elements/switch', array ('inline' => false, 'once' => true));

$model = (isset($model)? $model : 'Setting');

if (isset($sectionHide)) {
	$sectionHide = ' data-section-hide="'.h($sectionHide).'"';
} else {
	$sectionHide = '';
}

$ro = "";
$onCol = "success";

if (isset($readOnly) && $readOnly) {
	$ro = ' readonly="true" disabled="true"';
	$onIcon = "<i class='icon-lock'></i>";
	$offIcon = "<i class='icon-lock'></i>";
} elseif ($lock) {
	$onCol = "warning";
	$onIcon = "<i class='icon-lock'></i>";
	$offIcon = "<i class='icon-lock icon-white'></i>";
} else {
	$onIcon = "<i class='icon-ok'></i>";
	$offIcon = "<i class='icon-remove'></i>";
}?>

<input id="switch-<?=h($id)?>" class="switch" type="checkbox"<?=$sectionHide?> data-setting-name="<?=h($model.'.'.$name)?>" data-setting-value="<?=h(($value?'true':'false'))?>" data-setting-url="<?=h($url)?>" data-on="<?=$onCol?>" data-on-label="<?=$onIcon?>" data-off-label="<?=$offIcon?>" <?=$value?' checked':''?><?=$ro?>>
