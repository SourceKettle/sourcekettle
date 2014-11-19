<h3><?=__("Select your theme")?></h3>
<?php
$options = array('default' => __('%s default', $sourcekettle_config['UserInterface']['alias']['value']));
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
