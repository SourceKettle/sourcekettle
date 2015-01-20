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
		echo '<h3>'.__('Projects').'</h3>';
		echo '</div>';

		echo '<div class="row-fluid">';
		echo $this->element("linked_list", array(
			"listSetName" => "member-selection",
			"listSpan" => 6,
			"itemSpan" => 12,
			"lists" => array(
				__("Members") => array('id' => 'members-list', 'items' => $members, 'tooltip' => __('Projects that are part of the group')),
				__("Non-members") => array('id' => 'non-members-list', 'items' => $nonMembers, 'tooltip' => __('Projects that are not part of the group')),
			),
		));
		echo "</div><hr/>";

		echo '<div class="row-fluid">';
		echo '<h3>'.__('People').'</h3>';
		echo '</div>';
		echo '<div class="row-fluid">';
		echo $this->element("linked_list", array(
			"listSetName" => "permission-selection",
			"listSpan" => 3,
			"itemSpan" => 12,
			"lists" => array(
				__("Admin teams") => array('id' => 'admins-list', 'items' => $admins, 'tooltip' => __('Teams with admin permissions on the project group')),
				__("User teams") => array('id' => 'users-list', 'items' => $users, 'tooltip' => __('Teams with user permissions on the project group')),
				__("Guest teams") => array('id' => 'guests-list', 'items' => $guests, 'tooltip' => __('Teams with guest permissions on the project group')),
				__("Other teams") => array('id' => 'other-teams-list', 'items' => $otherTeams, 'tooltip' => __('Teams with no permissions on the project group')),
			),
		));
		echo "</div>";

            echo $this->Bootstrap->button(__('Update'), array("style" => "primary", "size" => "large", 'class' => 'controls'));

            echo $this->Form->end();
            ?>

<?= $this->Html->scriptBlock("
	$('form').submit(function(){
		$('#members-list').sortable('toArray').forEach(function(id){
			hidden = document.createElement('input');
			hidden.type = 'hidden';
			hidden.name = 'data[Project][]';
			hidden.value = id;
			$('form').append(hidden);
		});
	});
	$('form').submit(function(){
		$('#admins-list').sortable('toArray').forEach(function(id){
			hidden = document.createElement('input');
			hidden.type = 'hidden';
			hidden.name = 'data[GroupCollaboratingTeam]['+id+'][team_id]';
			hidden.value = id;
			$('form').append(hidden);

			hidden = document.createElement('input');
			hidden.type = 'hidden';
			hidden.name = 'data[GroupCollaboratingTeam]['+id+'][access_level]';
			hidden.value = 2;
			$('form').append(hidden);
		});
	});
	$('form').submit(function(){
		$('#users-list').sortable('toArray').forEach(function(id){
			hidden = document.createElement('input');
			hidden.type = 'hidden';
			hidden.name = 'data[GroupCollaboratingTeam]['+id+'][team_id]';
			hidden.value = id;
			$('form').append(hidden);

			hidden = document.createElement('input');
			hidden.type = 'hidden';
			hidden.name = 'data[GroupCollaboratingTeam]['+id+'][access_level]';
			hidden.value = 1;
			$('form').append(hidden);
		});
	});
	$('form').submit(function(){
		$('#guests-list').sortable('toArray').forEach(function(id){
			hidden = document.createElement('input');
			hidden.type = 'hidden';
			hidden.name = 'data[GroupCollaboratingTeam]['+id+'][team_id]';
			hidden.value = id;
			$('form').append(hidden);

			hidden = document.createElement('input');
			hidden.type = 'hidden';
			hidden.name = 'data[GroupCollaboratingTeam]['+id+'][access_level]';
			hidden.value = 0;
			$('form').append(hidden);
		});
	});
", array('inline' => false)); ?>
