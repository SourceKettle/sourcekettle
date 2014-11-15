<? if (isset($sectionHide)) {
	$sectionHide = ' data-section-hide="'.h($sectionHide).'"';
} else {
	$sectionHide = '';
}?>

<? if ($lock) { ?>
<input id="switch-<?=h($id)?>" class="switch" type="checkbox"<?=$sectionHide?> data-setting-name="<?=h($name)?>" data-setting-value="<?=h($value)?>" data-setting-url="<?=h($url)?>" data-on="warning" data-on-label="<i class='icon-lock'></i>" data-off-label="<i class='icon-lock icon-white'></i>" <?=$value?' checked':''?>>
<? } else { ?>
<input id="switch-<?=h($id)?>" class="switch" type="checkbox"<?=$sectionHide?> data-setting-name="<?=h($name)?>" data-setting-value="<?=h($value)?>" data-setting-url="<?=h($url)?>" data-on="success" data-on-label="<i class='icon-ok'></i>" data-off-label="<i class='icon-remove'></i>" <?=$value?' checked':''?>>
<? } ?>
