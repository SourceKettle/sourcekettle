<?= $this->element('beta_warning') ?>
<div class='hero-unit'>
    <h1>We &hearts; open-source</h1>
    <p>
        DevTrack is built using a variety of open-source software such as CakePHP, Bootstrap from Twitter and many other open-source projects.
        You can view our source and download DevTrack from GitHub <?=$this->Html->link('here', 'https://github.com/chrisbulmer/devtrack')?>.
    </p>
</div>

<div class="row">
    <div class="span12" style="text-align:center">
        <h2>Many Thanks To</h2>
    </div>
    <div class="span4" style="text-align:center">
        <h4>Those Lovely Open-Source Folk</h4>
        <h4><small>who share our passion</small></h4>
        <p><?=$this->Html->link('@cakephp', 'https://github.com/cakephp/')?></p>
        <p><?=$this->Html->link('@loadsys', 'https://github.com/loadsys/')?></p>
        <p><?=$this->Html->link('@twitter', 'https://github.com/twitter/')?></p>
    </div>
    <div class="span4" style="text-align:center">
        <h4>The DevTrack Dev Team</h4>
        <h4><small>for those sleepless months</small></h4>
        <p><?=$this->Html->link('@pwhittlesea', 'https://github.com/pwhittlesea/')?></p>
        <p><?=$this->Html->link('@chrisbulmer', 'https://github.com/chrisbulmer/')?></p>
        <p><?=$this->Html->link('@amn-ecs', 'https://github.com/amn-ecs/')?></p>
        <p><?=$this->Html->link('et al.', 'https://github.com/chrisbulmer/devtrack/graphs/contributors')?></p>
    </div>
    <div class="span4" style="text-align:center">
        <h4>The Many Who Inspired Us</h4>
        <h4><small>for challenging us to do better</small></h4>
        <p><?=$this->Html->link('@codebasehq', 'https://codebasehq.com/')?></p>
        <p><?=$this->Html->link('@bitbucket', 'https://bitbucket.org/')?></p>
        <p><?=$this->Html->link('@github', 'https://github.com/')?></p>
    </div>
    <div class="span12" style="text-align:center">
        <h5><small>and to whoever made us tea, we salute you!</small></h5>
    </div>
</div>
