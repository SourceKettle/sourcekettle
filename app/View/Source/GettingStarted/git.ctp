<?php
/**
 *
 * View class for APP/Source/gettingStarted for the DevTrack system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Source
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
$smallText = " <small>" . $project['Project']['description'] . " </small>";
$pname = $project['Project']['name'];

// Header for the page
echo $this->Bootstrap->page_header($pname . $smallText);

$public_key = $this->Popover->popover(
    'Public Key',
    "How does one acquisition a 'Public Key' good Sir?",
    "Here at DevTrack, we love making source code control easy as pie, unfortunately computers are more like an apple turnover.<br>
     <br>
     What this pop-up box is trying to say is:<br>
     <i>For the most up-to-date advice on SSH Keys, Google 'how to set up public private key &lt;OS&gt;'!</i><br>
     <br>
     We have kids!"
);

?>
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="span9">
        <div class="row">

            <div class="well span9">
                <h2>Getting started:</h2>
                <h3><?= $this->Bootstrap->label('1', 'info', array('style'=>'font-size:18px')) ?> Global setup:</h3>
    
<pre>git config --global user.name "<?= $user['name'] ?>"
git config --global user.email <?= $user['email'] ?></pre>
    
                <p><?= $this->Bootstrap->label('Why?', 'warning') ?> If you don't set up Git properly on your local machine when you commit, not only will Git complain, your team mates will!</p>
            </div>

            <div class="well span9">
                <h3><?= $this->Bootstrap->label('2', 'info', array('style'=>'font-size:18px')) ?> Get the code flowing:</h3>
                <p><?= $this->Bootstrap->label('Whoa there!', 'important') ?> Have you <?=$this->Html->link('uploaded',array('controller'=>'sshKeys','action'=>'add'))?> your <?=$public_key?>? Step 2 wont work if you haven't!</p>
                <h4><?= $this->Bootstrap->label('2a', 'info', array('style'=>'font-size:14px')) ?> Fresh Start?</h4>

<pre>mkdir <?= $project['Project']['name'] ?> 
cd <?= $project['Project']['name'] ?> 
git init
touch README
git add README
git commit -m 'First Commit'
git remote add origin git@<?= $_SERVER['SERVER_NAME'] ?>:projects/<?= $project['Project']['name'] ?>.git
git push -u origin master</pre>

                <h4><?= $this->Bootstrap->label('2b', 'info', array('style'=>'font-size:15px')) ?> Existing Git Repo?</h4>

<pre>cd existing_git_repo
git remote add origin git@<?= $_SERVER['SERVER_NAME'] ?>:projects/<?= $project['Project']['name'] ?>.git
git push -u origin master</pre>

                <p><?= $this->Bootstrap->label('Not sure?', 'warning') ?> If you don't quite get some of the commands above, head over to the Git documentation at <?= $this->Html->link('http://git-scm.com/docs', 'http://git-scm.com/docs') ?></p>
            </div>

            <div class="well span9">
                <h3><?= $this->Bootstrap->label('3', 'info', array('style'=>'font-size:18px')) ?> When you're done:</h3>
                <?= $this->Bootstrap->button_link('Press this unnecessary large, green button', array('project' => $project['Project']['name'], 'action' => 'tree'), array("style" => "success", "size" => "large")) ?>
            </div>

        </div>
    </div>
</div>
