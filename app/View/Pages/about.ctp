<?php
/**
 *
 * View class for APP/Pages/about for the SourceKettle system
 * Display the about page
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Help
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */


//echo $this->element('beta_warning'); // TODO show this for dev versions

?>
<div class='hero-unit'>
    <h1>We &hearts; open-source</h1>
    <p>
        SourceKettle is built using a variety of open-source software such as CakePHP, Bootstrap from Twitter and many other open-source projects.
        You can view our source and download SourceKettle from GitHub <?=$this->Html->link('here', 'https://github.com/SourceKettle/sourcekettle')?>.
    </p>
</div>

<div class="row-fluid">
    <div class="span12" style="text-align:center">
        <h2>Many Thanks To</h2>
    </div>
</div>
<div class="row-fluid">
    <div class="span4" style="text-align:center">
        <h4>Those Lovely Open-Source Folk</h4>
        <h4><small>who share our passion</small></h4>
        <p><?=$this->Html->link('@cakephp', 'https://github.com/cakephp/')?></p>
        <p><?=$this->Html->link('@loadsys', 'https://github.com/loadsys/')?></p>
        <p><?=$this->Html->link('@twitter', 'https://github.com/twitter/')?></p>
        <p><?=$this->Html->link('jQuery', 'http://jquery.com/')?></p>
        <p><?=$this->Html->link('DataTables', 'http://datatables.net/')?></p>
        <p><?=$this->Html->link('Bootstrap Switch', 'http://www.bootstrap-switch.org/')?></p>
    </div>
    <div class="span4" style="text-align:center">
        <h4>The SourceKettle Dev Team</h4>
        <h4><small>for those sleepless months</small></h4>
        <p><?=$this->Html->link('@antineutron', 'https://github.com/antineutron/')?></p>
        <p><?=$this->Html->link('@pwhittlesea', 'https://github.com/pwhittlesea/')?></p>
        <p><?=$this->Html->link('@chrisbulmer', 'https://github.com/chrisbulmer/')?></p>
        <p><?=$this->Html->link('et al.', 'https://github.com/SourceKettle/sourcekettle/graphs/contributors')?></p>
    </div>
    <div class="span4" style="text-align:center">
        <h4>The Many Who Inspired Us</h4>
        <h4><small>for challenging us to do better</small></h4>
        <p><?=$this->Html->link('@codebasehq', 'https://codebasehq.com/')?></p>
        <p><?=$this->Html->link('@bitbucket', 'https://bitbucket.org/')?></p>
        <p><?=$this->Html->link('@github', 'https://github.com/')?></p>
    </div>
</div>
<div class="row-fluid">
    <div class="span12" style="text-align:center">
        <h5><small>and to whoever made us tea, we salute you!</small></h5>
    </div>
    <?if($current_user['is_admin'] == 1):?>
    <div class="span12" style="text-align:center; font-size:50%">
          <a href='http://www.youtube.com/watch?v=GIM_2e4CBVs'>Tuba solo</a>.
    </div>
    <?endif?>

</div>
