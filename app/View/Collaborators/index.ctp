<?php
/**
 *
 * View class for APP/collaborators/index for the SourceKettle system
 * Allows modification of collaborators
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Collaborators
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$_project_name = $project['Project']['name'];
$_levels = array(
    '0' => array(
        'icon' => 'search',
        'text' => 'Guest',
        'action' => 'makeguest',
    ),
    '1' => array(
        'icon' => 'user',
        'text' => 'User',
        'action' => 'makeuser',
    ),
    '2' => array(
        'icon' => 'wrench',
        'text' => 'Admin',
        'action' => 'makeadmin',
    ),
);

$_up_icon   = $this->Bootstrap->icon('arrow-up');
$_down_icon = $this->Bootstrap->icon('arrow-down');

?>

<?= $this->DT->pHeader(__("Collaborators working on the project")) ?>
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="span10">
        <div class="row">
            <div class="span6">
                <div class="well">
                    <h3><?= __("Users collaborating on this project") ?></h3>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th width="60%"><?= __("User") ?></th>
                                <th><?= __("Role") ?></th>
                                <? if ($isAdmin) {?><th width="25%"><?= __("Actions") ?></th><? } ?>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach (array_reverse($_levels, true) as $_access_level => $_details) :

                            $_access_text   = $_details['text'];
                            $_access_icon = $this->Bootstrap->icon($_details['icon']);

                            foreach ($collaborators[$_access_level] as $collaborator) :

                                $_user_name = h($collaborator['User']['name']);
                                $_user_mail = h($collaborator['User']['email']);
                                $_user_id   = $collaborator['User']['id'];
                                $_user_url  = array('controller' => 'users', 'action' => 'view', $_user_id);
                                $_c_id      = $collaborator['Collaborator']['id'];

                                $_promote_url = ($_access_level < 2) ? $this->Html->url(array('project' => $_project_name, 'action' => $_levels[$_access_level + 1]['action'], $_c_id), true) : null;
                                $_demote_url  = ($_access_level > 0) ? $this->Html->url(array('project' => $_project_name, 'action' => $_levels[$_access_level - 1]['action'], $_c_id), true) : null;
                                $_delete_url  = $this->Html->url(array('controller' => 'collaborators', 'project' => $_project_name, 'action' => 'delete', $_c_id), true);

                                $_blank_button = $this->Bootstrap->button($this->Bootstrap->icon('none'), array('escape'=>false, 'size' => 'mini', 'class' => 'disabled'))
                            ?>
                            <tr>
                                <td><?= $this->Html->link("$_user_name &lt;$_user_mail&gt;", $_user_url, array('escape' => false)) ?></td>
                                <td><?= $_access_icon . " " . h($_access_text) ?></td>
								<? if ($isAdmin) {?>
                                <td>
                                    <? if ($_promote_url) echo $this->Bootstrap->button_form($_up_icon, $_promote_url, array('escape'=>false, 'size' => 'mini', 'title' => 'Promote user')); else echo $_blank_button; ?>
                                    <? if ($_demote_url) echo $this->Bootstrap->button_form($_down_icon, $_demote_url, array('escape'=>false, 'size' => 'mini', 'title' => 'Demote user')); else echo $_blank_button; ?>
                                <?php
                                    echo $this->Bootstrap->button_link(
                                        $this->Bootstrap->icon('eject', 'white'),
                                        $_delete_url,
                                        array('escape'=>false, 'style' => 'danger', 'size' => 'mini', 'title' => 'Remove user from project')
                                    );
                                ?>
                                </td>
								<? } ?>
                            </tr>
                            <?php
                            endforeach;
                        endforeach;
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="span4">
            <?php if ($isAdmin) {
                echo $this->Form->create('Collaborator',
                    array(
                        'url' => array(
                            'action' => 'add',
                            'project' => $project['Project']['name']
                        ),
                        'class' => 'form-inline well input-append'
                    )
                );

                echo '<h3>'. __("Add a User") .'</h3>';

               	echo $this->element('typeahead_input',
                    array(
						'url' => array(
            				'api' => true,
       					    'controller' => 'users',
            				'action' => 'autocomplete',
						),
                        'name' => 'name',
						'jsonListName' => 'users',
                        'properties' => array(
                            'id' => 'userSearchBox',
                            'class' => 'span3',
                            'placeholder' => 'john.smith@example.com',
                            'label' => false,
                        )
                    )
                );
                echo $this->Bootstrap->button($this->Bootstrap->icon('plus', 'white'), array('escape' => false, 'style' => 'success'));

                echo $this->Form->end();
            } ?>
            </div>
        </div>
        <div class="row">
            <div class="span6">
                <div class="well">
                    <h3><?= __("Teams collaborating on this project") ?></h3>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th width="60%"><?= __("Team name") ?></th>
                                <th><?= __("Role") ?></th>
                                <? if ($isAdmin) {?><th width="25%"><?= __("Actions") ?></th><? } ?>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach (array_reverse($_levels, true) as $_access_level => $_details) :

                            $_access_text   = $_details['text'];
                            $_access_icon = $this->Bootstrap->icon($_details['icon']);

                            foreach ($collaborating_teams[$_access_level] as $collaborator) :

                                $_team_name = h($collaborator['Team']['name']);
                                $_team_id   = $collaborator['Team']['id'];
                                $_team_url  = array('controller' => 'teams', 'action' => 'view', $_team_id);
                                $_c_id      = $collaborator['CollaboratingTeam']['id'];

                                $_promote_url = ($_access_level < 2) ? $this->Html->url(array('project' => $_project_name, 'action' => "team_".$_levels[$_access_level + 1]['action'], $_c_id), true) : null;
                                $_demote_url  = ($_access_level > 0) ? $this->Html->url(array('project' => $_project_name, 'action' => "team_".$_levels[$_access_level - 1]['action'], $_c_id), true) : null;
                                $_delete_url  = $this->Html->url(array('controller' => 'collaborators', 'project' => $_project_name, 'action' => 'team_delete', $_c_id), true);

                                $_blank_button = $this->Bootstrap->button($this->Bootstrap->icon('none'), array('escape'=>false, 'size' => 'mini', 'class' => 'disabled'))
                            ?>
                            <tr>
                                <td><?= $this->Html->link($_team_name, $_team_url, array('escape' => false)) ?></td>
                                <td><?= $_access_icon . " " . h($_access_text) ?></td>
								<? if ($isAdmin) {?>
                                <td>
                                    <? if ($_promote_url) echo $this->Bootstrap->button_form($_up_icon, $_promote_url, array('escape'=>false, 'size' => 'mini', 'title' => 'Promote user')); else echo $_blank_button; ?>
                                    <? if ($_demote_url) echo $this->Bootstrap->button_form($_down_icon, $_demote_url, array('escape'=>false, 'size' => 'mini', 'title' => 'Demote user')); else echo $_blank_button; ?>
                                <?php
                                    echo $this->Bootstrap->button_link(
                                        $this->Bootstrap->icon('eject', 'white'),
                                        $_delete_url,
                                        array('escape'=>false, 'style' => 'danger', 'size' => 'mini', 'title' => 'Remove team from project')
                                    );
                                ?>
                                </td>
								<? } ?>
                            </tr>
                            <?php
                            endforeach;
                        endforeach;
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="span4">
            <?php if ($isAdmin) {
                echo $this->Form->create('CollaboratingTeam',
                    array(
                        'url' => array(
                            'action' => 'add',
                            'project' => $project['Project']['name']
                        ),
                        'class' => 'form-inline well input-append'
                    )
                );

                echo '<h3>'. __("Add a Team") .'</h3>';

                echo $this->element('typeahead_input',
                    array(
                        'name' => 'name',
						'jsonListName' => 'teams',
						'url' => array(
            				'api' => true,
       					   	'controller' => 'teams',
            				'action' => 'autocomplete',
						),
                        'properties' => array(
                            'id' => 'teamSearchBox',
                            'class' => 'span3',
                            "placeholder" => __("Start typing to search..."),
                            'label' => false,
                        )
                    )
                );

                echo $this->Bootstrap->button($this->Bootstrap->icon('plus', 'white'), array('escape' => false, 'style' => 'success'));

                echo $this->Form->end();
            } ?>
            </div>
        </div>

		<? if (!empty($group_collaborating_teams[0]) || !empty($group_collaborating_teams[1]) || !empty($group_collaborating_teams[2])) { ?>
        <div class="row">
		<?=__("The following teams have access via project groups - you must ask a system administrator to change these permissions:")?>

		<ul>
		<?php
        foreach (array_reverse($_levels, true) as $_access_level => $_details) {

            $_access_text   = $_details['text'];
            $_access_icon = $this->Bootstrap->icon($_details['icon']);

			foreach ($group_collaborating_teams[$_access_level] as $collaborator) {
				echo "<li>".h($collaborator['Team']['name'])." (".h($_access_text).")</li>";
			}
		} ?>
		</ul>
        </div>
		<? } ?>
    </div>
</div>
