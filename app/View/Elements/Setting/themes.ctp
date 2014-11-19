<h3><?=__("Select your theme")?></h3>
<?php

// If the settings are system-wide, show the default as 'SourceKettle default' - we are referring to the software defaults
if (isset($systemWide) && $systemWide) {
	$alias = 'SourceKettle';

// Otherwise it's the system default, so use the system alias
} else {
	$alias = $sourcekettle_config['UserInterface']['alias']['value'];
}

$options = array('default' => __('%s default', $alias));
foreach ($this->TwitterBootswatch->getThemes() as $a => $theme) {

    $options[$a] = $this->Popover->popover(
        $theme['name'],
        $theme['name'].' Preview',
        '<ul class="thumbnails">
            <li>
                <a href="#" class="thumbnail">
                    <img src="'.$theme['thumbnail'].'" alt="">
                </a>
            </li>
        </ul>
        '.$theme['description']
    );

} ?>
<?= $this->Bootstrap->radio("Setting.UserInterface.theme", array("label" => false, "options" => $options)) ?>
<?= $this->Bootstrap->button("Update", array("style" => "primary", 'class' => 'controls')) ?>
