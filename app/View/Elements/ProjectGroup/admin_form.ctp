<?php
/**
 *
 * View class for APP/teams/admin_add for the SourceKettle system
 * View allow admin to create a new team
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2014
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Teams
 * @since         SourceKettle v 1.5
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

            echo '<h3>'.__('Project group details').'</h3>';

			echo $this->Form->input('id');

            echo $this->Bootstrap->input("name", array(
                "input" => $this->Form->text("name", array("class" => "span5")),
            ));

            echo $this->Bootstrap->input("description", array(
                "input" => $this->Form->text("description", array("class" => "span11")),
            ));

		echo '<div class="row-fluid">';
		echo $this->element("linked_list", array(
			"listSpan" => 6,
			"itemSpan" => 12,
			"lists" => array(
				__("Members") => array('id' => 'members-list', 'items' => $members, 'tooltip' => __('Projects that are part of the group')),
				__("Non-members") => array('id' => 'non-members-list', 'items' => $nonMembers, 'tooltip' => __('Projects that are not part of the group')),
			),
		));
		echo "</div>";

            echo $this->Bootstrap->button(__('Update'), array("style" => "primary", "size" => "large", 'class' => 'controls'));

            echo $this->Form->end();
            ?>

<?= $this->Html->scriptBlock("
	$('form').submit(function(){
		$('#members-list').sortable('toArray').forEach(function(taskId){
			hidden = document.createElement('input');
			hidden.type = 'hidden';
			hidden.name = 'data[Project][]';
			hidden.value = taskId;
			$('form').append(hidden);
		});
	});
", array('inline' => false)); ?>
