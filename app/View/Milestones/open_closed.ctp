<?php
/**
 *
 * View class for APP/milestones/open|closed for the SourceKettle system
 * Shows a list of milestones for a project
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	 SourceKettle Development Team 2012
 * @link		  http://github.com/SourceKettle/sourcekettle
 * @package	   SourceKettle.View.Milestones
 * @since		 SourceKettle v 0.1
 * @license	   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$this->Html->css('milestones.index', null, array ('inline' => false));

// Hacky... 
$status = preg_replace('/^view_/', '', $this->request['action']);

?>

<div class="row-fluid">
	<?= $this->element('Milestone/topbar') ?>
</div>
<div class="row-fluid">
		<?php
		if (empty($milestones)) {
			echo '<div class="span10" style="text-align:center"><h1>'.__("No $status milestones").'</h1></div>';
		} else {
			foreach ($milestones as $milestone) {
				echo $this->element('Milestone/block', array('milestone' => $milestone));
			}
		}
		?>
</div>
