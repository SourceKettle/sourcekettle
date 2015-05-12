<?php
/**
 *
 * View class for APP/Source/gettingStarted for the SourceKettle system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	 SourceKettle Development Team 2012
 * @link		  https://github.com/SourceKettle/sourcekettle
 * @package	   SourceKettle.View.Source
 * @since		 SourceKettle v 0.1
 * @license	   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

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
	<div class="well span10 offset1">
		<h2>Getting started:</h2>
		<h3><?= $this->Bootstrap->label('1', 'info', array('style'=>'font-size:18px')) ?> Global setup:</h3>
	
<pre>git config --global user.name "<?= h($user['name']) ?>"
git config --global user.email <?= h($user['email']) ?></pre>

		<p><?= $this->Bootstrap->label('Why?', 'warning') ?> If you don't set up Git properly on your local machine when you commit, not only will Git complain, your team mates will!</p>
	</div>
</div>

<div class="row-fluid">

	<div class="well span10 offset1">
		<h3><?= $this->Bootstrap->label('2', 'info', array('style'=>'font-size:18px')) ?> Get the code flowing:</h3>
		<p><?= $this->Bootstrap->label('Whoa there!', 'important') ?> Have you <?=$this->Html->link('uploaded',array('controller'=>'sshKeys','action'=>'add'))?> your <?=$public_key?>? Step 2 wont work if you haven't!</p>
		<h4><?= $this->Bootstrap->label('2a', 'info', array('style'=>'font-size:14px')) ?> Fresh Start?</h4>

		<p>
		You can simply clone your empty repository:
		<pre>git clone <?= $this->Source->scmUri($project) ?></pre>
		...then create some code and start checking it in!
		</p>

		<p>
		Or, if you prefer, you can create a local repo and connect it to SourceKettle:

<pre>mkdir <?= h($project['Project']['name']) ?> 
cd <?= h($project['Project']['name']) ?> 
git init
touch README
git add README
git commit -m 'First Commit'
git remote add origin <?= $this->Source->scmUri($project) ?> 
git push -u origin master</pre>

		(Here we add an empty README file. You should add some useful documentation here once the code's going strong!)
		</p>
		<h4><?= $this->Bootstrap->label('2b', 'info', array('style'=>'font-size:15px')) ?> Existing Git Repo?</h4>

<pre>cd existing_git_repo
git remote add origin <?= $this->Source->scmUri($project) ?> 
git push -u origin master</pre>
				<p><?= $this->Bootstrap->label('Not sure?', 'warning') ?> If you don't quite get some of the commands above, head over to the Git documentation at <?= $this->Html->link('http://git-scm.com/docs', 'http://git-scm.com/docs') ?></p>


		<h4><?= $this->Bootstrap->label('2c', 'info', array('style'=>'font-size:18px')) ?> Not a command-line junkie? Get a GUI!</h4>
		<p>
		  There are <a href='http://git-scm.com/downloads/guis'>various</a> <a href='https://git.wiki.kernel.org/index.php/Interfaces,_frontends,_and_tools#Giggle'>GUI interfaces</a> available for browsing git repositories, and many IDEs now have git integration. Important: whatever client you use, it MUST be able to use SSH public/private key pairs or you will not be able to connect to SourceKettle! Here's some handy links for some of the tools we've been asked about:
		  <ul>
			<li><a href='http://code.google.com/p/tortoisegit/'>TortoiseGit</a>: Popular windows-only git client, integrates directly into the windows file browser. <a href='http://code.google.com/p/tortoisegit/wiki/UsingPuTTY'>This page</a> has instructions on using it with SSH keys (a bit fiddly)</li>
			<li><a href='http://wiki.eclipse.org/EGit'>Eclipse</a> (cross-platform): Using EGit (you may want to read <a href='http://wiki.eclipse.org/EGit/User_Guide#Eclipse_SSH_Configuration'>this page</a> to get SSH keys up and running)</li>
			<li><a href='https://netbeans.org/kb/docs/ide/git.html'>NetBeans</a> (cross-platform) also has git support, with SSH keys</li>
		  </ul>
		</p>

		<h4><?= $this->Bootstrap->label('2d', 'info', array('style'=>'font-size:18px')) ?> Need to check out your code?</h4>
		<p>
		You and your collaborators can clone the repository like so:
		<pre>git clone <?= $this->Source->scmUri($project) ?></pre>
		</p>
	</div>
</div>
<div class="row-fluid">
	<div class="well span10 offset1">
		<h3><?= $this->Bootstrap->label('3', 'info', array('style'=>'font-size:18px')) ?> When you're done:</h3>
		<?= $this->Bootstrap->button_link('Press this unnecessarily large, green button', array('project' => $project['Project']['name'], 'action' => 'tree'), array("style" => "success", "size" => "large")) ?>
	</div>


</div>
