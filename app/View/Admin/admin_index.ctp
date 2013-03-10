<?php
/**
 *
 * View class for APP/admin/index for the DevTrack system
 * View will render a stats for the system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/SourceKettle/devtrack
 * @package       DevTrack.View.Admin
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
$this->extend('/Common/sidebar_layout_admin');

$this->assign('title', $this->Bootstrap->page_header('Administration <small>system overview</small>'));

$cakePath = APP . 'Console' . DS . 'cake -app ' . APP . ' cake_daemon.daemon ';

$daemonChart = $this->GoogleChart->create();
$daemonChart->setType('line');
$daemonChart->setSize(600, 200)->setMargins(0, 0, 0, 0);
$daemonChart->addAxis('y', array('labels' => range(0, $daemonData['maxNodes'])));
$daemonChart->addAxis('x', array('labels' => array($daemonData['firstTime'], $daemonData['lastTime'])));

$daemonChart->addData($daemonData['numNodes'], array('color' => 'FF0000'));
$daemonChart->addData($daemonData['runningNodes'], array('color' => '00FF00'));
$daemonChart->addData($daemonData['queueLength'], array('color' => '0000FF'));

?>
<!-- Modals -->
<div id="startModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h3 id="myModalLabel">Starting the Background Daemon...</h3>
	</div>
	<div class="modal-body">
		<p>
			To start your background daemon, please run the following command at a command prompt:<br>
			<code><?= $cakePath ?>start</code><br>
			When you are done, and all has gone well, click the green 'Done' button!
		</p>
	</div>
	<div class="modal-footer">
		<button class="btn btn-success" data-dismiss="modal" aria-hidden="true">Done</button>
	</div>
</div>
<div id="stopModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h3 id="myModalLabel">Stopping the Background Daemon...</h3>
	</div>
	<div class="modal-body">
		<p>
			To stop your background daemon, please run the following command at a command prompt:<br>
			<code><?= $cakePath ?>stop</code><br>
			When you are done, and all has gone well, click the green 'Done' button!
		</p>
	</div>
	<div class="modal-footer">
		<button class="btn btn-success" data-dismiss="modal" aria-hidden="true">Done</button>
	</div>
</div>
<div class="row-fluid">
	<div class="span6">
		<div class="well">
			<h3>
				<?= __('Background Daemon') ?>
				<span class="pull-right">
					<?= $this->element('Setting/on_off_buttons', array('dataToggle' => 'modal', 'action'=> ($daemonState)?'#stopModal':'#startModal', 'value' => $daemonState, 'words' => 1)) ?>
				</span>
			</h3>
			<ul class="thumbnails">
				<li class="span12">
					<a href="#" class="thumbnail">
						<?= $daemonChart ?>
					</a>
				</li>
			</ul>
			<h5>Recurring tasks:</h5>
			<dl class="dl-horizontal">
				<dt>Ssh Sync</dt>
				<dd>Last run <?= $this->Time->timeAgoInWords($lastSshKeyRun) ?></dd>
				<dt>Monitor</dt>
				<dd>Last run <?= $this->Time->timeAgoInWords($lastMonitorRun) ?></dd>
			</dl>
		</div>
	</div>
	<div class="span6">
		<div class="well">
			<div class="row">
			</div>
		</div>
	</div>
</div>
