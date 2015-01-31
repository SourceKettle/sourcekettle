<?php
/**
 *
 * View class for APP/Source/gettingStarted for the SourceKettle system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          https://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Source
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
$smallText = " <small>source code</small>";
$pname = $project['Project']['name'];

// Header for the page
echo $this->Bootstrap->page_header($pname . $smallText);

$public_key = $this->Popover->popover(
    'Public Key',
    "How does one acquire a 'Public Key' good Sir?",
    "Here at SourceKettle, we love making source code control easy as pie, unfortunately computers are more like an apple turnover.<br>
     <br>
     What this pop-up box is trying to say is:<br>
     <i>For the most up-to-date advice on SSH Keys, Google 'how to set up public private key &lt;OS&gt;'!</i><br>
     <br>
     We have kids!"
);

?>
<div class="row-fluid">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="span9">
        <div class="row-fluid">

            <div class="well span9">
                <h3><?= $this->Bootstrap->label('1', 'info', array('style'=>'font-size:18px')) ?> Getting started:</h3>
                <p><?= $this->Bootstrap->label('Whoa there!', 'important') ?> Have you <?=$this->Html->link('uploaded',array('controller'=>'sshKeys','action'=>'add'))?> your <?=$public_key?>? Step 1 wont work if you haven't!</p>

                <pre>svn checkout svn+ssh://<?= $sourcekettle_config['SourceRepository']['user']['value'] ?>@<?= $_SERVER['SERVER_NAME'] ?>:projects/<?= h($project['Project']['name']) ?>.svn</pre>

            </div>

            <div class="well span9">
                <h3><?= $this->Bootstrap->label('2', 'info', array('style'=>'font-size:18px')) ?> When you're done:</h3>
                <?= $this->Bootstrap->button_link('Press this unnecessarily large, green button', array('project' => $project['Project']['name'], 'action' => 'tree'), array("style" => "success", "size" => "large")) ?>
            </div>

        </div>
    </div>
</div>
