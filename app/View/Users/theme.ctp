<?php
/**
 *
 * View class for APP/users/theme for the SourceKettle system
 * Shows a list of themes for a user to pick from
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          https://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Users
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

echo $this->Bootstrap->page_header($this->request->data['User']['name']); ?>

<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/users') ?>
    </div>
    <div class="span6">
        <?= $this->Form->create('User', array('class' => 'well form-horizontal', 'type' => 'post')) ?>
		<?= $this->element('Setting/themes') ?>
        <?= $this->Form->end() ?>
    </div>
    <div class="span4">
        <h3><?=__("Where do these magical themes originate?")?></h3>
        <p>
            <?=__("Here at %s, we like making our own decisions.", $sourcekettle_config['UserInterface']['alias']['value'])?>
            <?=__("Like, should I put the milk in my tea before the water?")?>
            <?=__("Thankfully, some lovely folks over at %s host some themes that we can use to make SourceKettle <strong>Super Pretty</strong>.", $this->Html->link('Bootswatch', 'http://bootswatch.com/'), $sourcekettle_config['UserInterface']['alias']['value'])?>
        </p>
        <h3><?=__("So what's the catch?")?></h3>
        <p>
            <?=__("Well, as we didn't design them, we can't guarentee they will actually look perfect.")?>
            <?=__("Some of the gadgets, gizmos and thingymabobs may not look quite right.<br>")?>
            <?=__("Want to help us make SourceKettle prettier? Tell us whats wrong, over on %s.", $this->Html->link('GitHub', 'https://github.com/SourceKettle/sourcekettle')) ?>
        </p>
        <br>
        <h3><?=__("What do our developers think?")?></h3>
        <blockquote>
            <p>Awesome!</p>
            <small>@chriswbulmer</small>
        </blockquote>
        <blockquote class="pull-right">
            <p>I'm actually in love with these!</p>
            <small>@pwhittlesea</small>
        </blockquote>
    </div>
</div>
