<?php
/**
 *
 * Default layout for the SourceKettle system
 * Layout which all views will render inside
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	 SourceKettle Development Team 2012
 * @link		  https://github.com/SourceKettle/sourcekettle
 * @package	   SourceKettle.View.Layouts
 * @since		 SourceKettle v 0.1
 * @license	   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<!DOCTYPE html>
<html>
<head>
	<title>
	<?= h($sourcekettle_config['UserInterface']['alias']['value']) ?>
	<? if(isset($pageTitle)) {?>
		 - <?= h($pageTitle) ?>
	<? } ?>
	</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<?= $this->Html->meta('favicon.ico', $this->Html->url('/favicon.ico'), array('type' => 'icon')) ?>
	
	<?= $this->Html->charset('UTF-8') ?>

	<?= $this->Html->css('/bootstrap/css/bootstrap.min') ?>

	<?= $this->Theme->css($sourcekettle_config) ?>

	<?= $this->Html->css('layout'); ?>

	<?= $this->Html->css('/bootstrap/css/bootstrap-responsive.min') ?>

	<?//= $this->fetch ('css') ?>

	<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>
<body>
	<header>
		<?if(isset($this->params['admin']) && $this->params['admin'] == 1){?>
		<div class="navbar navbar-admin navbar-fixed-top">
		<?} else {?>
		<div class="navbar navbar-inverse navbar-fixed-top">
		<?}?>
			<div class="navbar-inner">
				<div class="container-fluid">
					<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>
					<?= $this->Html->link($sourcekettle_config['UserInterface']['alias']['value'], '/', array('class' => 'brand')); ?>


					<ul class="nav pull-right">
					<? if(isset($current_user)){ ?>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<?= $this->Gravatar->image(
									$current_user['email'],
									array('size' => 20),
									array('alt' => $current_user['name'])
								) ?> <?= h($current_user['name']) ?>
								<b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li><?= $this->Html->link(__('View profile'), array ('admin' => false, 'controller' => 'users', 'action' => 'view', $current_user['id'])) ?></li>
								<li><?= $this->Html->link(__('Account settings'), array ('admin' => false, 'controller' => 'users', 'action' => 'index')) ?></li>
								<li class="divider"></li>
								<li><?= $this->Html->link(__('Log Out'), array ('admin' => false, 'controller' => 'login', 'action' => 'logout')) ?></li>
							</ul>
						</li>
					</ul>
					<div class="nav-collapse">
						<ul class="nav">
							<?php
							$navItems = array(
								'dashboard' => __('Dashboard'),
								'projects' => __('Projects'),
								'kanban' => __('My Kanban'),
								'help' => __('Help'),
							);
							if($current_user && $current_user['is_admin'] == 1){
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
						<ul class="nav pull-right">
							<li><a href="#" onclick="toggleDistractions(); return false;"><i class="icon-fullscreen icon-white"></i></a></li>
						</ul>
					</div>
					<? } else { ?>
						</ul>
							<div class="form-group navbar-login">
        						<?= $this->Form->create('User', array('url' => array('controller' => 'login', 'action' => 'index'))) ?>
							<?=$this->Form->text("email", array("placeholder" => __("email address"), 'tabindex'=>'1', 'autofocus' => ''))?>
							<?=$this->Form->password("password", array("placeholder" => __("password"), 'tabindex'=>'2'))?>
							<?=$this->Form->button("Log in", array('type' => 'submit', 'class' => 'btn btn-primary', 'tabindex'=>'3'))?>
							</div>
						</form>
					<? } ?>
				</div>
			</div>
		</div>
	</header>
	<div class="container-fluid" id="content-wrapper">
		<span id="flashes">
			<?= $this->Bootstrap->flashes(array('auth' => true, 'closable' => true)); //Bootstrap equivalent of $this->Session->flash() ?>
			<?= $this->Session->flash('email'); ?>
		</span>

		<? if(isset($pageTitle)) {
			$header = $pageTitle;
			if (isset($subTitle)) {
				$header .= ' <small>'.h($subTitle).'</small>';
			}
			echo '<span class="distractions">'.$this->TwitterBootstrap->page_header($header)."</span>";
	
		} ?>
		
		<? // Optional sidebar
		if (isset($sidebar)) {
			echo '<div class="row-fluid">';
			echo '<div class="span2 distractions" id="sidebar-area">';
			echo $this->element("Sidebar/$sidebar");
			echo '</div>';
			echo '<div class="span10" id="page-area">';
			echo $content_for_layout;
			echo '</div>';
		} else {
			echo $content_for_layout;
		} ?>
	</div>
	<footer class="span12">
		<hr>
		<?=$this->Html->link("About SourceKettle $sourcekettleVersion", '/about');?>
		<?=$this->Html->link('Git help', 'http://git-scm.com/book/en/Getting-Started-Git-Basics');?>
		<?//=$this->Html->link('SVN help', '/svn_help');?>
	</footer>

	<!-- JavaScript! Placed at the end of the file for faster page loading -->
	<?= $this->Html->script('/jquery/jquery-1.11.0.min.js'); ?>
	<?= $this->Html->script('/jquery-ui/jquery-ui-1.10.4.min.js'); ?>
	<?= $this->Html->script('/jquery-color/jquery.color-2.1.2.min'); ?>
	<?= $this->Html->script('/bootstrap/js/bootstrap.min') ?>
	<?= $this->Html->script('/bootstrap-tooltip/bootstrap-tooltip');?>
	<?= $this->Html->script('sourcekettle');?>
	<?= $this->Popover->requirements() ?>
	<?= $this->fetch('scriptBottom') ?>
	<?= $scripts_for_layout ?>
	<?= $this->Js->writeBuffer() ?>
</body>
</html>
