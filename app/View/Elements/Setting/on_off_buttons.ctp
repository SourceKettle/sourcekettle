<?php
$_ri0 = $this->Bootstrap->icon('remove');
$_ri1 = $this->Bootstrap->icon('ok');
if (strpos($action, '#') === false) {
	$_ru0 = $this->Html->url(array('admin' => true, 'controller' => 'settings', 'action' => $action, 0), true);
	$_ru1 = $this->Html->url(array('admin' => true, 'controller' => 'settings', 'action' => $action, 1), true);
	$_ro0 = $_ro1 = array('escape' => false);
} else {
	$_ru0 =  $_ru1 = $action;
	$_ro0 = $_ro1 = array('data-toggle' => $dataToggle, 'role' => "button");
}

if ($value) {
    $_ri1 = $this->Bootstrap->icon('ok', 'white');
    $_ro1['style'] = 'success';
    $_ro1['class'] = 'disabled';
    $_ON  = $this->Bootstrap->button(((isset($words)) ? __('on') : $_ri1), $_ro1);
    $_OFF = $this->Bootstrap->button_link(((isset($words)) ? __('off') : $_ri0), $_ru0, $_ro0);
} else {
    $_ri0 = $this->Bootstrap->icon('remove', 'white');
    $_ro0['style'] = 'danger';
    $_ro0['class'] = 'disabled';
    $_ON  = $this->Bootstrap->button_link(((isset($words)) ? __('on') : $_ri1), $_ru1, $_ro1);
    $_OFF = $this->Bootstrap->button(((isset($words)) ? __('off') : $_ri0), $_ro0);
}
echo '<div class="btn-group">';
echo $_ON;
echo $_OFF;
echo '</div>';
