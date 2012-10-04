<?php
/**
 *
 * Default layout for the DevTrack system
 * Layout which all views will render inside
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Layouts
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>
        <?= $devtrack_config['global']['alias'] ?> - <?= $title_for_layout ?>
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= $this->Html->meta('favicon.ico', $this->Html->url('/favicon.ico'), array('type' => 'icon')) ?>
    <?= $this->Html->charset('UTF-8') . "\n" ?>
    <?= $this->Html->css('bootstrap.min') ?>
    <?= ($user_theme != 'default') ? $this->TwitterBootswatch->cssForTheme($user_theme) : '' ?>
    <?= $this->Html->css('layout'); ?>
    <?= $this->Html->css('bootstrap-responsive.min') ?>
    <?= $this->fetch ('css') ?>

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body>
    <header>
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <?= $this->Html->link($devtrack_config['global']['alias'], '/', array('class' => 'brand')); ?>

                    <?php
                    if(isset($user_name)){
                        ?>
                    <div class="btn-group pull-right" id='login-button'>
                        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="icon-user"></i> <?= $user_name?>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><?= $this->Html->link (__('Account settings'), array ('controller' => 'users')) ?></li>
                            <li class="divider"></li>
                            <li><?= $this->Html->link (__('Log Out'), array ('controller' => 'login', 'action' => 'logout')) ?></li>
                        </ul>
                    </div>
                    <?
                    } else {
                    ?>
                    <?= $this->Html->link (
                        '<i class="icon-user"></i>' . __('Login'),
                        array ('controller' => 'login'),
                        array ('class' => 'btn pull-right', 'id' => 'login-button', 'escape' => false)) ?>
                    <?php
                    }?>
                    <div class="nav-collapse">
                        <ul class="nav">
                            <?php
                            $navItems = array(
                            	'dashboard' => __('Dashboard'),
                            	'projects' => __('Projects'),
                                'help' => __('Help'),
                            );
                            if($user_is_admin){
                                $navItems['admin'] = __('Administration');
                            }
                            foreach ($navItems as $controller => $text) {
                                echo "<li" . ($controller == $this->params['controller'] ? " class='active'>" : ">");
                                echo $this->Html->link($text, array ('controller' => $controller, 'action' => 'index'));
                                echo "</li>";
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div class="container">
        <div id="content">
            <span id="flashes">
                <? echo $this->Bootstrap->flashes(array('auth' => true, 'closable' => true)); //Bootstrap equivalent of $this->Session->flash() ?>
            </span>
            <?= $content_for_layout ?>
        </div>
        <div id='footer'>
            <hr>
            <?=$this->Html->link("About DevTrack $devtrackVersion", '/about');?>
            <?=$this->Html->link('Git help', 'http://git-scm.com/book/en/Getting-Started-Git-Basics');?>
            <?//=$this->Html->link('SVN help', '/svn_help');?>
        </div>
    </div>

    <!-- JavaScript! Placed at the end of the file for faster page loading -->
    <?= $this->Html->script('jquery.min.js'); ?>
    <?= $this->Html->script('bootstrap.min') ?>
    <?= $this->Popover->requirements() ?>
    <?= $this->fetch('scriptBottom') ?>
    <?= $scripts_for_layout ?>
    <?= $this->Js->writeBuffer() ?>
</body>
</html>
