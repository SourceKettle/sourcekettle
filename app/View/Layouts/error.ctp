<?php
/**
 *
 * Error layout for the SourceKettle system
 * A simpler, non-dynamic layout to render error pages in. 
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          https://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Layouts
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

try {
    $sourcekettle_config = ClassRegistry::init('Setting')->loadConfigSettings();
} catch (Exception $e){
    $sourcekettle_config = array('UserInterface' => array('alias' => array('value' => 'SourceKettle')));
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>
        <?= h($sourcekettle_config['UserInterface']['alias']['value']) ?> - <?= h($title_for_layout) ?>
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= $this->Html->meta('favicon.ico', $this->Html->url('/favicon.ico'), array('type' => 'icon')) ?>
    <?= $this->Html->charset('UTF-8') . "\n" ?>
    <?= $this->Html->css('/bootstrap/css/bootstrap.min') ?>
    <?= $this->Html->css('layout'); ?>
    <?= $this->Html->css('/bootstrap/css/bootstrap-responsive.min') ?>
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
                <div class="container-fluid">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <?= $this->Html->link($sourcekettle_config['UserInterface']['alias']['value'], '/', array('class' => 'brand')); ?>

                    <ul class="nav pull-right">
                    <? if(isset($user_name)){ ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-user icon-white"></i> <?= h($user_name) ?><b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><?= $this->Html->link(__('Account settings'), array ('admin' => false, 'controller' => 'users', 'action' => 'index')) ?></li>
                                <li class="divider"></li>
                                <li><?= $this->Html->link(__('Log Out'), array ('admin' => false, 'controller' => 'login', 'action' => 'logout')) ?></li>
                            </ul>
                        </li>
                    <? } ?>
                    </ul>
                    <div class="nav-collapse">
                        <ul class="nav">
                            <?php
                            $navItems = array(
                                'dashboard' => __('Dashboard'),
                                'projects' => __('Projects'),
                                'help' => __('Help'),
                            );
                            if(isset($user_is_admin) && $user_is_admin) {
                                $navItems['admin'] = __('Administration');
                            }

                            // Make the projects nav element highlighted if the current page is anything to do with a project
                            $current_controller = $this->params['controller'];
                            if (isset($project)){
                                $current_controller = 'projects';
                            }

                            foreach ($navItems as $controller => $text) {
                                echo "<li" . ($controller == $current_controller ? " class='active'>" : ">");
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
            <?= $content_for_layout ?>
        </div>
        <div id='footer'>
            <hr>
            <?=$this->Html->link("About SourceKettle", '/about');?>
            <?=$this->Html->link('Git help', 'http://git-scm.com/book/en/Getting-Started-Git-Basics');?>
            <?//=$this->Html->link('SVN help', '/svn_help');?>
        </div>
    </div>

    <!-- JavaScript! Placed at the end of the file for faster page loading -->
    <?= $this->Html->script('/jquery/jquery-1.11.0.min.js'); ?>
    <?= $this->Html->script('/bootstrap/js/bootstrap.min') ?>
</body>
</html>
