<?php
/**
 *
 * View class for APP/users/admin_index for the DevTrack system
 * View will render lists of users
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/SourceKettle/devtrack
 * @package       DevTrack.View.Users
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

echo $this->Bootstrap->page_header('Administration <small>search for persons of interest</small>'); ?>

<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/admin') ?>
    </div>
    <div class="span10">
        <div class="row-fluid">
            <?php
                $_promote_icon = $this->Bootstrap->icon('arrow-up');
                $_demote_icon  = $this->Bootstrap->icon('arrow-down');
                $_delete_icon  = $this->Bootstrap->icon('eject', 'white');
                $_edit_icon    = $this->Bootstrap->icon('pencil');
                echo $this->Form->create('User',
                    array(
                        'class' => 'form-inline input-append'
                    )
                );

                echo $this->element('components/user_typeahead_input',
                    array(
                        'name' => 'name',
                        'properties' => array(
                            'id' => 'appendedInputButton',
                            'class' => 'span11',
                            "placeholder" => "john.smith@example.com",
                            'label' => false
                        )
                    )
                );
                echo $this->Bootstrap->button('Search', array('escape' => false, 'style' => 'primary'));

                echo $this->Form->end();
            ?>
            <table class="well table table-striped">
                <thead>
                    <tr>
                        <th width="85%"><?=__("User name / email")?></th>
                        <th><?=__("Actions")?></th>
                    </tr>
                </thead>
                <tbody>
                <? foreach ( $users as $user ) : ?>
                    <tr>
                        <td>
                            <?= $this->Html->link($user['User']['name'], array('action' => 'view', $user['User']['id']))?>
                            &lt;<?= $this->Html->link($user['User']['email'], 'mailto:'.$user['User']['email']) ?>&gt;
                        </td>
                        <td>
                        <?php
                                $_promote_url = $this->Html->url(
									array(
										'controller' => 'users',
										'action' => 'admin_promote',
										$user['User']['id']
									), true
								);

                                $_demote_url = $this->Html->url(
									array(
										'controller' => 'users',
										'action' => 'admin_demote',
										$user['User']['id']
									), true
								);

								$_edit_url = $this->Html->url(
									array(
											'controller' => 'users',
											'action' => 'admin_edit',
											$user['User']['id']
										 ), true
								);

                                $_delete_url = $this->Html->url(
									array(
										'controller' => 'users',
										'action' => 'admin_delete',
										$user['User']['id']
									), true
								);


							echo "<div class='btn-group'>\n";
                            echo $this->Bootstrap->button_link(
								$_edit_icon, $_edit_url,
                                array('escape'=>false, 'size' => 'mini', 'title' => __('Edit account details'))
                            );
							if($user['User']['is_admin']){
                            	echo $this->Bootstrap->button_form(
									$_demote_icon, $_demote_url,
									array('escape'=>false, 'size' => 'mini', 'title' => __('Demote system admin to normal user')),
									__("Are you sure you want to remove admin privileges from ")." ".h($user['User']['email'])."?"
								);
							} else {
                            	echo $this->Bootstrap->button_form(
									$_promote_icon, $_promote_url,
									array('escape'=>false, 'size' => 'mini', 'title' => __('Promote user to system admin')),
									__("Are you sure you want to make")." ".h($user['User']['email'])." ".__("a system admin?")
								);
							}
                            echo $this->Bootstrap->button_form(
								$_delete_icon, $_delete_url,
                                array('escape'=>false, 'style' => 'danger', 'size' => 'mini', 'class' => '', 'title' => __('Delete user')),
                                __("Are you sure you want to delete")." " . h($user['User']['email']) . "?"
                            );
							echo "</div>\n";
                        ?>
                        </td>
                    </tr>
                <? endforeach; ?>
                </tbody>
            </table>
            <?= $this->element('pagination') ?>
        </div>
    </div>
</div>
