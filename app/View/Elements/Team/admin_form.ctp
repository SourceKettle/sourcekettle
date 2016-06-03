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

			echo $this->Form->input('id');
            echo '<h3>'.__('Update team details').'</h3>';

            echo $this->Bootstrap->input(__("name"), array(
                "input" => $this->Form->text("name", array("class" => "span5")),
            ));

            echo $this->Bootstrap->input(__("description"), array(
                "input" => $this->Form->text("description", array("class" => "span11")),
            ));

			echo '<div class="row-fluid">';
			echo $this->element("linked_list", array(
				"listSpan" => 6,
				"itemSpan" => 12,
				"lists" => array(
					__("Members") => array('id' => 'members-list', 'items' => $members, 'tooltip' => __('Users that are members of the team')),
					__("Non-members") => array('id' => 'non-members-list', 'items' => $nonMembers, 'tooltip' => __('Users that are not members of the team')),
				),
			));
		echo "</div>";


            echo $this->Bootstrap->button(__('Update'), array("style" => "primary", "size" => "large", 'class' => 'controls'));

echo $this->Html->scriptBlock("
	$('form').submit(function(){
		$('#members-list').sortable('toArray').forEach(function(id){
			hidden = document.createElement('input');
			hidden.type = 'hidden';
			hidden.name = 'data[User][id][]';
			hidden.value = id;
			$('form').append(hidden);
		});
	});
", array('inline' => false));
